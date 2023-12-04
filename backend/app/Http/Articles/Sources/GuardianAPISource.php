<?php

namespace App\Http\Articles\Sources;

use Psr\Http\Message\ResponseInterface;

class GuardianAPISource extends BaseAPISource
{
	public const KEY = 'guardian';

	private int $numberOfPages;
	public function parseAndGetArticlesArrayFromResponse(ResponseInterface $response): array
	{
		$res = json_decode($response->getBody(), true)['response'];
		$this->numberOfPages = $res['pages'];
		return $res['results'];
	}

	public function getURL(): string
	{
		return env('GUARDIAN_URL');
	}

	public function getQueryParams(): string
	{
		$beginDate = now()->parse($this->getLastSyncedArticlePublishedAt())->format('Y-m-d\TH:i:s\Z');
		$endDate = now()->format('Y-m-d');
		$offset = $this->offset + 1;

		return "?from-date=$beginDate&to-date=$endDate" .
			"&order-by=oldest&page={$offset}&show-fields=body,trailText&show-tags=keyword" .
			"&api-key=" . env("GUARDIAN_API_KEY");
	}

	public function getOptions(): array
	{
		return [];
	}

	public function getTitle(): string
	{
		return $this->article["webTitle"];
	}

	/**
	 * No Source provided for Guardian articles in their API.
	 *
	 * @return string|null
	 */
	public function getSource(): ?string
	{
		return "Guardian";
	}

	public function getDescription(): ?string
	{
		return $this->article["fields"]['trailText'];
	}

	public function getPublishedAt(): ?string
	{
		return now()->parse($this->article["webPublicationDate"]);
	}

	public function getContent(): string
	{
		return $this->article['fields']["body"];
	}

	/**
	 * Despite what they said about using show-references=author in the QP and
	 * that will show the author it did not work at all.
	 *
	 * @return string
	 */
	public function getAuthor(): string
	{
		return 'Guardian';
	}

	public function getCategory(): string
	{
		return $this->article["type"];
	}
	public function hasNewPage(): bool
	{
		return ($this->offset + 1) < $this->numberOfPages;
	}

	public function getKeywords(): array
	{
		$tags = collect($this->article["tags"]);
		return $tags->pluck('sectionName')->toArray();
	}
}
