<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

    Route::resource('articles', ArticleController::class);
    Route::get('articles-search-data', [ArticleController::class, 'searchData']);
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['prefix' => 'users'], function () {
            Route::get('get-preferences', [UserController::class, 'getPreferences']);
            Route::post('store-preferences', [UserController::class, 'setPreferences']);
        });
    });
});
