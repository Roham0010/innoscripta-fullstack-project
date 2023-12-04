<?php

use App\Http\Articles\GetArticlesAPI;
use App\Http\Articles\Sources\GuardianAPISource;
use App\Http\Articles\Sources\NewsAPISource;
use App\Http\Articles\Sources\NYTimesAPISource;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    echo "Please note that sometimes there are some errors on the api servers that may crash one of the apis and
	we can't sync other resources because it's a local sync queue. (tought it's not neccessary to use horizon) <br/>
	Starting to fetch <br/>";
    $api = new NewsAPISource();
    GetArticlesAPI::dispatch($api)->onQueue('sync');

    $api = new GuardianAPISource();
    GetArticlesAPI::dispatch($api)->onQueue('sync');

    $api = new NYTimesAPISource();
    GetArticlesAPI::dispatch($api)->onQueue('sync');
});
