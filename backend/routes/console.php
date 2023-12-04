<?php

use App\Http\Articles\GetArticlesAPI;
use App\Http\Articles\Sources\GuardianAPISource;
use App\Http\Articles\Sources\NewsAPISource;
use App\Http\Articles\Sources\NYTimesAPISource;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('get:articles', function () {
	echo "Please note that sometimes there are some errors on the api servers that may crash one of the apis and
	we can't sync other resources because it's a local sync queue. (tought it's not neccessary to use horizon) \n
	Starting to fetch \n";
	$api = new NewsAPISource();
	GetArticlesAPI::dispatch($api)->onQueue('sync');

	$api = new GuardianAPISource();
	GetArticlesAPI::dispatch($api)->onQueue('sync');

	$api = new NYTimesAPISource();
	GetArticlesAPI::dispatch($api)->onQueue('sync');
	echo "Ended\n";
})->purpose('Display an inspiring quote');
