<?php

namespace App\Http\Articles;

use App\Http\Articles\Sources\ArticlesAPIInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetArticlesAPI implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private ArticlesAPIInterface $articleAPI;
	public function __construct(ArticlesAPIInterface $articleAPI)
	{
		$this->articleAPI = $articleAPI;
	}

	/**
	 * @return void
	 */
	public function handle(): void
	{
		$articles = $this->articleAPI::dispatch();
	}
}
