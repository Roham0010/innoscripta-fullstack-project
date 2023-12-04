<?php

namespace App\Http\Articles\Sources;

use App\Models\Article;
use App\Models\SyncStatus;
use App\Models\Keyword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BaseAPISource implements ArticlesAPIInterface, ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public const KEY = '';
	public array $articles;
	public array $article;
	public int $offset = 0;

	protected string $lastArticlePublishedAt;

	public function __construct(int $offset = 0)
	{
		$this->offset = $offset;
	}

	public function handle(): void
	{
		$this->getArticles();

		if (array_filter($this->articles)) {
			$this->handleArticles();

			$this->saveLastArticlePublishedAt();

			$this->checkAndDispatchForNextPage();
		}
	}

	/**
	 * Getting the articles from the source we are currently in.
	 *
	 * @return void
	 */
	private function getArticles(): void
	{
		$this->articles = [];
		$httpClient = new \GuzzleHttp\Client();
		echo "Get Data From: " . ($this->getURL() . $this->getQueryParams()) . "\n <br />";
		try {
			$response = $httpClient->get(
				$this->getURL() . $this->getQueryParams(),
				array_merge($this->getOptions(), [
					"Accept" => "application/json",
				])
			);
		} catch (\Throwable $e) {
			// Hanlde error codes and other things.
			if ($e->getCode() == 421) {
				// rate limit, ignoring
			} else {
				throw $e;
				// Log error into sentry or other logging systems, ignoring it to avoid errors
				report($e);
			}
		}

		if (isset($response)) {
			$this->articles = $this->parseAndGetArticlesArrayFromResponse($response);
			// dd($this->articles);
		}
	}

	/**
	 * Get data of the article model from the coresponding api source.
	 * Saving the article keywords.
	 *
	 * @return void
	 */
	private function handleArticles(): void
	{
		foreach ($this->articles as $rawArticle) {
			$this->setArticle($rawArticle);
			if (!$this->getTitle()) {
				continue;
			}

			$articleData = $this->generateArticleDataForDatabase();

			try {
				$article = Article::query()->create($articleData);
				$this->setLastPublishedAt();

				$this->handleKeywords($article);
			} catch (\Throwable $e) {
				if (Str::contains($e->getMessage(), '1366 Incorrect string value')) {
					// TODO
					// Characters other than uft8 can be handled by changing the columns encoding
					// ignoring the article for now
				} else {
					report($e);
				}
			}
		}
	}

	/**
	 * Preparing the article data for database.
	 *
	 * @return array
	 */
	private function generateArticleDataForDatabase(): array
	{
		return [
			'from_api' => static::KEY,
			'source' => trim(substr($this->getSource(), 0, 64)),
			'title' => trim(substr($this->getTitle(), 0, 128)),
			'description' => trim(substr($this->getDescription(), 0, 512)),
			'body' => trim(substr($this->getContent(), 0, 65000)),
			'author' => trim(substr($this->getAuthor(), 0, 64)),
			'category' => trim(substr($this->getCategory(), 0, 32)),
			'published_at' => $this->getPublishedAt(),
		];
	}

	/**
	 * Saving each article keywords and assigning them to the article.
	 *
	 * @param Article $article
	 * @return void
	 */
	private function handleKeywords(Article $article)
	{
		$keywords = $this->getKeywords();

		$keywordIds = [];
		foreach (array_filter($keywords) as $keyword) {
			$keyword = Keyword::query()->firstOrCreate([
				'name' => $keyword,
			]);
			$keywordIds[] = $keyword->id;
		}

		count($keywordIds) && $article->keywords()->sync($keywordIds);
	}

	/**
	 * Get the last sync status for the latest article to continue from where we left of
	 * on the previous sync of each source.
	 *
	 * @return void
	 */
	protected function getLastSyncedArticlePublishedAt()
	{
		return $this->lastArticlePublishedAt ??
			SyncStatus::query()->where('source_name', static::KEY)->first()->last_article_published_at ??
			now()->subDays(10);
	}

	/**
	 * Setting the latest published at base on the current and new articles
	 * published at, we will set it when the new published at is bigger than
	 * the previous value.
	 *
	 * @return void
	 */
	protected function setLastPublishedAt()
	{
		if (
			!isset($this->lastArticlePublishedAt) ||
			$this->getPublishedAt() > now()->parse($this->lastArticlePublishedAt)
		) {
			$this->lastArticlePublishedAt = $this->getPublishedAt();
		}
	}

	/**
	 * Saving the latest article published at in sync statuses so that
	 * the next time we start from that date.
	 *
	 * @return void
	 */
	private function saveLastArticlePublishedAt()
	{
		SyncStatus::query()
			->updateOrCreate(
				['source_name' => static::KEY],
				['last_article_published_at' => $this->lastArticlePublishedAt]
			);
	}
	/**
	 * Checking if the source still has any pages left for this search or not.
	 *
	 * @return void
	 */
	private function checkAndDispatchForNextPage(): void
	{
		if ($this->hasNewPage() && count($this->articles)) {
			self::dispatch($this->offset + 1);
		}
	}

	public function parseAndGetArticlesArrayFromResponse(ResponseInterface $res): array
	{
		return [];
	}
	public function setArticle(array $article): void
	{
		$this->article = $article;
	}
	public function getURL(): string
	{
		return "";
	}

	public function getQueryParams(): string
	{
		return "";
	}

	public function getOptions(): array
	{
		return [];
	}

	public function getTitle(): string
	{
		return "";
	}

	public function getSource(): ?string
	{
		return "";
	}

	public function getDescription(): ?string
	{
		return "";
	}

	public function getKeywords(): array
	{
		return [];
	}

	public function getPublishedAt(): ?string
	{
		return "";
	}

	public function getContent(): string
	{
		return "";
	}

	public function getAuthor(): string
	{
		return "";
	}

	public function getCategory(): string
	{
		return "";
	}

	public function hasNewPage(): bool
	{
		return false;
	}
}
