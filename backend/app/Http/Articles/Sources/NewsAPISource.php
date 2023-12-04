<?php

namespace App\Http\Articles\Sources;

use Psr\Http\Message\ResponseInterface;

class NewsAPISource extends BaseAPISource
{
	public const KEY = 'newsapi';

	public function parseAndGetArticlesArrayFromResponse(ResponseInterface $response): array
	{
		return json_decode($response->getBody(), true)['articles'];
	}

	public function getURL(): string
	{
		return env('NEWSAPI_URL');
	}

	public function getQueryParams(): string
	{
		$from = str_replace(' ', 'T', $this->getLastSyncedArticlePublishedAt());
		$to = str_replace(' ', 'T', now()->subDay()->endOfDay());
		// NewsAPI offset starts from 1
		$offset = $this->offset + 1;

		return "?q=tech&from=$from&to=$to&sortBy=publishedAt&page=$offset&apiKey=" . env("NEWSAPI_API_KEY");
	}

	public function getOptions(): array
	{
		return [];
	}

	public function getTitle(): string
	{
		return $this->article["title"];
	}

	public function getSource(): ?string
	{
		return $this->article['source']['name'];
	}

	public function getDescription(): ?string
	{
		return $this->article["description"];
	}

	public function getImageURL(): ?string
	{
		return $this->article["urlToImage"];
	}

	public function getPublishedAt(): ?string
	{
		$publishedAt = now()->parse($this->article["publishedAt"]);
		return $publishedAt < now()->subYears(15) ? now()->subYears(15) : $publishedAt;
	}

	public function getContent(): string
	{
		return $this->article["content"];
	}

	public function getAuthor(): string
	{
		return $this->article["author"] ?? 'newsapi';
	}

	/**
	 * NewsAPI doesn't have any category field.
	 *
	 * @return string
	 */
	public function getCategory(): string
	{
		return "news";
	}

	/**
	 * NewsAPI doesn't allow more than 5 pages to be retrived with a developer key.
	 *
	 * @return boolean
	 */
	public function hasNewPage(): bool
	{
		return $this->offset > 4;
	}
}
