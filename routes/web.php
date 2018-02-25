<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;


Route::get('/', 'DashboardController@index')->name('home');
Route::get('/logout', 'DashboardController@logout')->name('logout');
Route::get('/login', 'DashboardController@loginPage')->name('login');
Route::post('/login', 'DashboardController@login')->name('loginOperation');

Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UsersController@index')->name('listUsers');
    Route::post('/add', 'UsersController@store')->name('createUser');

    Route::group(['prefix' => '{user}'], function () {
        Route::post('/edit', 'UsersController@update')->name('updateUser');
        Route::post('/edit-password', 'UsersController@updatePassword')->name('updateUserPassword');
        Route::post('/delete', 'UsersController@destroy')->name('deleteUser');
    });
});

Route::group(['prefix' => 'news'], function () {
    Route::get('/', 'NewsController@index')->name('listNews');
    Route::post('/add', 'NewsController@store')->middleware('auth')->name('createNew');

    Route::group(['prefix' => '{new}'], function () {
        Route::post('/toggle-approval', 'NewsController@toggleApproval')->name('toggleApproval');
        Route::post('/edit', 'NewsController@update')->middleware('auth')->name('updateNew');
        Route::post('/delete', 'NewsController@destroy')->name('deleteNew');

        Route::group(['prefix' => 'comments'], function () {
            Route::get('/get-approved-comments', 'CommentsController@getApprovedComments')->middleware('auth')->name('listApprovedComments');
            Route::get('/', 'CommentsController@index')->name('listComments');
            Route::post('/add', 'CommentsController@store')->middleware('auth')->name('createComment');

            Route::group(['prefix' => '{comment}'], function () {
                Route::post('/toggle-approval', 'CommentsController@toggleApproval')->name('toggleApproval');
                Route::post('/edit', 'CommentsController@update')->middleware('auth')->name('updateComment');
                Route::post('/delete', 'CommentsController@destroy')->name('deleteComment');
            });
        });
    });
});

