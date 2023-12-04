<?php

namespace App\Http\Articles\Sources;

use Psr\Http\Message\ResponseInterface;

interface ArticlesAPIInterface
{
	/**
	 * @return array
	 */
	public function parseAndGetArticlesArrayFromResponse(ResponseInterface $response): array;
	/**
	 * @param array $article
	 */
	public function setArticle(array $article): void;
	public function getURL(): string;
	public function getQueryParams(): string;
	/**
	 * @return array
	 */
	public function getOptions(): array;
	public function getTitle(): string;
	public function getSource(): ?string;
	public function getDescription(): ?string;
	public function getPublishedAt(): ?string;
	public function getContent(): string;
	public function getAuthor(): string;
	public function getCategory(): string;
	public function hasNewPage(): bool;
	public function getKeywords(): array;
}
