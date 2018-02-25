<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/authenticate', 'JWTController@authenticate');
Route::get('/logout', 'JWTController@logout');

Route::group(['prefix' => 'news', 'middleware' => 'auth:api'], function () {
    Route::post('/add', 'NewsController@store')->name('createNew');

    Route::group(['prefix' => '{new}'], function () {
        Route::post('/edit', 'NewsController@update')->name('updateNew');

        Route::group(['prefix' => 'comments'], function () {
            Route::get('/get-approved-comments', 'CommentsController@getApprovedComments')->name('listApprovedComments');
            Route::post('/add', 'CommentsController@store')->name('createComment');
            Route::post('{comment}/edit', 'CommentsController@update')->name('updateComment');
        });
    });
});
