<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication.
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('sidebar/home', [
	'uses' => 'SidebarController@getHome',
	'middleware' => 'auth'
	]);

// API.
Route::get('api/bookmarks', [
	'uses' => 'Api\BookmarkController@index',
	'middleware' => 'api.auth'
	]);

Route::post('api/bookmarks', [
	'uses' => 'Api\BookmarkController@store',
	'middleware' => 'api.auth'
	]);

Route::get('api/bookmarks/ajax', 'Api\BookmarkController@helper');