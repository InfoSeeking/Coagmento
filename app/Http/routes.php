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

Route::get('/apitest', function () {
	return view('apitest');
});

// Authentication.
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Back-end pages.
Route::get('sidebar/home', [
	'uses' => 'SidebarController@getHome',
	'middleware' => 'auth'
	]);

Route::get('workspace', [
	'uses' => 'WorkspaceController@index',
	'middleware' => 'auth'
	]);

Route::post('workspace/projects/create', [
	'uses' => 'WorkspaceController@createProject',
	'middleware' => 'auth'
	]);

Route::delete('workspace/projects/{project_id}', [
	'uses' => 'WorkspaceController@deleteProject',
	'middleware' => 'auth'
	]);

Route::get('workspace/projects/{project_id}', [
	'uses' => 'WorkspaceController@viewProject',
	'middleware' => 'auth'
	]);

Route::get('workspace/projects/{project_id}/bookmarks/{bookmark_id}', [
	'uses' => 'WorkspaceController@viewBookmark',
	'middleware' => 'auth'
	]);

// API.

// Bookmarks.
Route::get('api/v1/bookmarks', [
	'uses' => 'Api\BookmarkController@index',
	'middleware' => 'api.auth'
	]);

Route::get('api/v1/bookmarks/{bookmark_id}', [
	'uses' => 'Api\BookmarkController@get',
	'middleware' => 'api.auth'
	]);

Route::post('api/v1/bookmarks', [
	'uses' => 'Api\BookmarkController@create',
	'middleware' => 'api.auth'
	]);

Route::put('api/v1/bookmarks/{bookmark_id}', [
	'uses' => 'Api\BookmarkController@update',
	'middleware' => 'api.auth'
	]);

Route::put('api/v1/bookmarks/{bookmark_id}/move', [
	'uses' => 'Api\BookmarkController@move',
	'middleware' => 'api.auth'
	]);

Route::delete('api/v1/bookmarks/{bookmark_id}', [
	'uses' => 'Api\BookmarkController@delete',
	'middleware' => 'api.auth'
	]);

// Projects.
Route::get('api/v1/projects', [
	'uses' => 'Api\ProjectController@index',
	'middleware' => 'api.auth'
	]);

Route::get('api/v1/projects/{project_id}', [
	'uses' => 'Api\ProjectController@get',
	'middleware' => 'api.auth'
	]);

Route::put('api/v1/projects/{project_id}', [
	'uses' => 'Api\ProjectController@update',
	'middleware' => 'api.auth'
	]);

Route::post('api/v1/projects', [
	'uses' => 'Api\ProjectController@create',
	'middleware' => 'api.auth'
	]);

Route::delete('api/v1/projects/{project_id}', [
	'uses' => 'Api\ProjectController@delete',
	'middleware' => 'api.auth'
	]);

Route::get('api/v1/projects/{project_id}/tags', [
	'uses' => 'Api\ProjectController@getTags',
	'middleware' => 'api.auth'
	]);