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
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', 'SplashController@index');
Route::get('/new', 'SplashController@index');
Route::post('/new/notify', 'SplashController@notify');

// Authentication.
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::post('auth/demoLogin', 'Auth\AuthController@demoLogin');

// Back-end pages.
Route::get('sidebar', [
	'uses' => 'SidebarController@getProjectSelection',
	'middleware' => 'auth'
	]);
Route::get('sidebar/{project_id}', [
	'uses' => 'SidebarController@getFeed',
	'middleware' => 'auth'
	]);

Route::get('workspace', [
	'uses' => 'WorkspaceController@showHome',
	'middleware' => 'auth'
	]);

Route::get('workspace/projects', [
	'uses' => 'WorkspaceController@showProjects',
	'middleware' => 'auth'
	]);

Route::get('workspace/projects/create', [
	'uses' => 'WorkspaceController@showProjectCreate',
	'middleware' => 'auth'
	]);

Route::get('workspace/projects/sharedWithMe', [
	'uses' => 'WorkspaceController@showShared',
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

Route::get('workspace/projects/{project_id}/settings', [
	'uses' => 'WorkspaceController@viewProjectSettings',
	'middleware' => 'auth'
	]);

Route::get('workspace/projects/{project_id}/bookmarks/{bookmark_id}', [
	'uses' => 'WorkspaceController@viewBookmark',
	'middleware' => 'auth'
	]);

Route::get('workspace/projects/{project_id}', [
	'uses' => 'WorkspaceController@viewProject'
	]);

Route::get('workspace/projects/{project_id}/bookmarks', [
	'uses' => 'WorkspaceController@viewProjectBookmarks'
	]);

Route::get('workspace/projects/{project_id}/snippets', [
	'uses' => 'WorkspaceController@viewProjectSnippets'
	]);

// API.

// User.
Route::get('api/v1/users/current', [
	'uses' => 'Api\UserController@getCurrent',
	'middleware' => 'api.auth'
	]);

Route::get('api/v1/users', [
	'uses' => 'Api\UserController@get',
	]);

Route::post('api/v1/users', [
	'uses' => 'Api\UserController@create'
	]);

Route::get('api/v1/users/logout', function(){
	Auth::logout();
});

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
	'middleware' => 'api.optional.auth'
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

Route::delete('api/v1/projects', [
	'uses' => 'Api\ProjectController@deleteMultiple',
	'middleware' => 'api.auth'
	]);

Route::get('api/v1/projects/{project_id}/tags', [
	'uses' => 'Api\ProjectController@getTags',
	'middleware' => 'api.optional.auth'
	]);

Route::post('api/v1/projects/{project_id}/share', [
	'uses' => 'Api\ProjectController@share',
	'middleware' => 'api.optional.auth'
	]);

// Snippets.
Route::post('api/v1/snippets', [
	'uses' => 'Api\SnippetController@create',
	'middleware' => 'api.auth'
	]);

Route::get('api/v1/snippets/{snippet_id}', [
	'uses' => 'Api\SnippetController@get',
	'middleware' => 'api.optional.auth'
	]);

Route::get('api/v1/snippets', [
	'uses' => 'Api\SnippetController@index',
	'middleware' => 'api.auth'
	]);

Route::put('api/v1/snippets/{snippet_id}', [
	'uses' => 'Api\SnippetController@update',
	'middleware' => 'api.auth'
	]);

Route::delete('api/v1/snippets/{snippet_id}', [
	'uses' => 'Api\SnippetController@delete',
	'middleware' => 'api.auth'
	]);

// Pages.
Route::post('api/v1/pages', [
	'uses' => 'Api\PageController@create',
	'middleware' => 'api.auth'
	]);

Route::get('api/v1/pages/{page_id}', [
	'uses' => 'Api\PageController@get',
	'middleware' => 'api.optional.auth'
	]);

Route::get('api/v1/pages', [
	'uses' => 'Api\PageController@index',
	'middleware' => 'api.optional.auth'
	]);

Route::delete('api/v1/pages/{page_id}', [
	'uses' => 'Api\PageController@delete',
	'middleware' => 'api.auth'
	]);

// Chat.
Route::post('api/v1/chat_messages', [
	'uses' => 'Api\ChatController@create',
	'middleware' => 'api.auth'
	]);

Route::get('api/v1/chat_messages', [
	'uses' => 'Api\ChatController@getMultiple',
	'middleware' => 'api.optional.auth'
	]);