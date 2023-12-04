<?php

namespace App\Http\Articles\Sources;

use Psr\Http\Message\ResponseInterface;

/**
 * NyTimes api only allows to get articles per day because the accepted date format
 * for the bedin_date parameter is /\d{8}/ that acceps dates like 20120508.
 * By having on time scheduled queues and sync statuses that is implemented
 * we will not have any problem in production, but in development mode if we run
 * the job multiple time at a short period of time there may be some duplicated articles.
 */
class NYTimesAPISource extends BaseAPISource
{
	public const KEY = 'nytimes';

	public function parseAndGetArticlesArrayFromResponse(ResponseInterface $response): array
	{
		return json_decode($response->getBody(), true)['response']['docs'];
	}

	public function getURL(): string
	{
		return env('NYTIMES_URL');
	}

	public function getQueryParams(): string
	{
		$beginDate = now()->parse($this->getLastSyncedArticlePublishedAt())->format('Ymd');
		$endDate = now()->format('Ymd');

		return "?begin_date=$beginDate&end_date=$endDate&sort=oldest&page={$this->offset}&api-key=" . env("NYTIMES_API_KEY");
	}

	public function getOptions(): array
	{
		return [];
	}

	public function getTitle(): string
	{
		return $this->article['headline']['main'] ?? '';
	}

	public function getSource(): ?string
	{
		return $this->article['source'] ?? '';
	}

	public function getDescription(): ?string
	{
		return $this->article['abstract'];
	}

	public function getPublishedAt(): ?string
	{
		return now()->parse($this->article["pub_date"]);
	}

	public function getContent(): string
	{
		return $this->article['lead_paragraph'];
	}

	public function getAuthor(): string
	{
		return $this->article['byline']['original'] ?? 'NYTimes';
	}

	public function getCategory(): string
	{
		return $this->article["document_type"];
	}

	/**
	 * NYTimes provide only 5 pages for developer account for each search.
	 *
	 * @return boolean
	 */
	public function hasNewPage(): bool
	{
		return $this->offset < 6;
	}

	public function getKeywords(): array
	{
		$keywords = collect($this->article["keywords"]);
		return $keywords->where('rank', '<', 6)->pluck('value')->toArray();
	}
}
