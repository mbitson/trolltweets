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

Route::get('/', function () {
    return view('sap');
});

Route::prefix('api')->group(function(){
    Route::post('filters', 'FiltersController@save');
    Route::get('filters', 'FiltersController@show');
    Route::get('hashtags/top/{limit}', 'HashtagController@top');
    Route::get('hashtags/summary', 'HashtagController@summary');
    Route::get('hashtags/count', 'HashtagController@count');
    Route::get('tweets/top/{limit}', 'TweetController@top');
    Route::get('tweets/summary', 'TweetController@summary');
    Route::get('tweets/count', 'TweetController@count');
});