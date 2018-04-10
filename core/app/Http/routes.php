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

// Spash page.
Route::get('/', 'SplashController@index');
Route::get('/new', 'SplashController@index');
Route::post('/new/notify', 'SplashController@notify');
Route::get('/confirm', function(){
    return view('auth.confirm');
});





// Authentication.
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLoginWithOldCoagmentoSupport');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::post('auth/demoLogin', 'Auth\AuthController@demoLogin');


// Sidebar pages.
Route::group(['middleware' => 'sidebar.auth'], function() {
	Route::get('sidebar', 'SidebarController@getProjectSelection');
	Route::get('sidebar/project/{project_id}', 'SidebarController@getFeed');
});

Route::get('sidebar/auth/login', 'SidebarController@getSidebarLogin');
Route::post('sidebar/auth/login', 'SidebarController@postLoginWithOldCoagmentoSupport');
Route::get('sidebar/auth/logout', 'SidebarController@getLogout');
Route::post('sidebar/auth/demoLogin', 'SidebarController@demoLogin');



// Workspace pages.
Route::group(['middleware' => 'auth'], function() {
    Route::get('stages', 'StageProgressController@directToStage');
    Route::get('stages/next', 'StageProgressController@moveToNextStage');
	// These pages do not make sense without a logged in user.
	Route::get('workspace', 'WorkspaceController@viewPanel');
	Route::get('workspace/projects', 'WorkspaceController@showProjects');
	Route::get('workspace/projects/create', 'WorkspaceController@showProjectCreate');
	Route::get('workspace/projects/sharedWithMe', 'WorkspaceController@showShared');
	Route::post('workspace/projects/create', 'WorkspaceController@createProject');
	Route::get('workspace/user/settings', 'WorkspaceController@showUserSettings');
	Route::post('workspace/user/settings', 'WorkspaceController@updateUserSettings');
});

Route::get('workspace/projects/{project_id}/bookmarks/{bookmark_id}',
	'WorkspaceController@viewBookmark');
Route::get('workspace/projects/{project_id}', 'WorkspaceController@viewProject');
Route::get('workspace/projects/{project_id}/bookmarks',
	'WorkspaceController@viewProjectBookmarks');
Route::get('workspace/projects/{project_id}/snippets', 'WorkspaceController@viewProjectSnippets');
Route::get('workspace/projects/{project_id}/chat', 'WorkspaceController@viewChat');
Route::get('workspace/projects/{project_id}/docs', 'WorkspaceController@viewDocs');
Route::get('workspace/projects/{project_id}/history', 'WorkspaceController@viewHistory');

// Viewing document requires write permissions until we can get read-only to work.
Route::get('workspace/projects/{project_id}/docs/{doc_id}', 'WorkspaceController@viewDoc');

Route::delete('workspace/projects/{project_id}', 'WorkspaceController@deleteProject');
Route::get('workspace/projects/{project_id}/settings', 'WorkspaceController@viewProjectSettings');

// API.
Route::group(['middleware' => 'api.auth'], function() {
	// These endpoints do not make sense without a logged in user.
	Route::get('api/v1/users/current', 'Api\UserController@getCurrent');
	Route::get('api/v1/users/logout', function(){
		Auth::logout();
	});
	Route::get('api/v1/projects', 'Api\ProjectController@index');
});

Route::group(['middleware' => 'api.optional.auth'], function(){
	// These routes may require some permissions, but not necessarily.
	// Users.
	Route::get('api/v1/users/{user_id}', 'Api\UserController@get');
	Route::post('api/v1/users', 'Api\UserController@create');
	Route::get('api/v1/users', 'Api\UserController@getMultiple');

	// Bookmarks.
	Route::get('api/v1/bookmarks', 'Api\BookmarkController@index');
	Route::get('api/v1/bookmarks/{bookmark_id}', 'Api\BookmarkController@get');
	Route::post('api/v1/bookmarks', 'Api\BookmarkController@create');
	Route::put('api/v1/bookmarks/{bookmark_id}', 'Api\BookmarkController@update');
	Route::put('api/v1/bookmarks/{bookmark_id}/move', 'Api\BookmarkController@move');
	Route::delete('api/v1/bookmarks/{bookmark_id}', 'Api\BookmarkController@delete');

	// Projects.
	Route::get('api/v1/projects/{project_id}', 'Api\ProjectController@get');
	Route::put('api/v1/projects/{project_id}', 'Api\ProjectController@update');
	Route::post('api/v1/projects', 'Api\ProjectController@create');
	Route::delete('api/v1/projects/{project_id}', 'Api\ProjectController@delete');
	Route::delete('api/v1/projects', 'Api\ProjectController@deleteMultiple');
	Route::get('api/v1/projects/{project_id}/tags', 'Api\ProjectController@getTags');
	Route::post('api/v1/projects/{project_id}/share', 'Api\ProjectController@share');
	Route::put('api/v1/projects/{project_id}/share', 'Api\ProjectController@updateShare');
	Route::delete('api/v1/projects/{project_id}/share', 'Api\ProjectController@unshare');

	// Snippets.
	Route::post('api/v1/snippets', 'Api\SnippetController@create');
	Route::get('api/v1/snippets/{snippet_id}', 'Api\SnippetController@get');
	Route::get('api/v1/snippets', 'Api\SnippetController@index');
	Route::put('api/v1/snippets/{snippet_id}', 'Api\SnippetController@update');
	Route::delete('api/v1/snippets/{snippet_id}', 'Api\SnippetController@delete');

	// Pages.
	Route::post('api/v1/pages', 'Api\PageController@create');
	Route::get('api/v1/pages/{page_id}', 'Api\PageController@get');
	Route::get('api/v1/pages', 'Api\PageController@index');
	Route::delete('api/v1/pages/{page_id}', 'Api\PageController@delete');

	// Queries.
	Route::post('api/v1/queries', 'Api\QueryController@create');
	Route::get('api/v1/queries/{query_id}', 'Api\QueryController@get');
	Route::get('api/v1/queries', 'Api\QueryController@index');
	Route::delete('api/v1/queries/{query_id}', 'Api\QueryController@delete');	

	// Chat.
	Route::post('api/v1/chatMessages', 'Api\ChatController@create');
	Route::get('api/v1/chatMessages', 'Api\ChatController@getMultiple');

	// Docs.
	Route::post('api/v1/docs', 'Api\DocController@create');
	Route::get('api/v1/docs', 'Api\DocController@getMultiple');
	Route::delete('api/v1/docs/{doc_id}', 'Api\DocController@delete');
	Route::get('api/v1/docs/{doc_id}/text', 'Api\DocController@getText');
});




//Stages
Route::get('/welcome', function () {
    return view('welcome');
});
Route::post('/welcome', 'StageProgressController@moveToNextStage');


Route::get('/questionnaire_pretask', function () {
    return view('questionnaire_pretask');
});
Route::post('/questionnaire_pretask', 'StageProgressController@moveToNextStage');

Route::get('/questionnaire_pretask', function () {
    return view('questionnaire_pretask');
});
Route::post('/questionnaire_pretask', 'StageProgressController@moveToNextStage');

Route::get('/task_description', function () {
    return view('task_description');
});
Route::post('/task_description', 'StageProgressController@moveToNextStage');

Route::get('/task', function () {
    return view('task');
});
Route::post('/task', 'StageProgressController@moveToNextStage');

Route::get('/questionnaire_posttask', function () {
    return view('questionnaire_posttask');
});
Route::post('/questionnaire_posttask', 'StageProgressController@moveToNextStage');

Route::get('/end', function(){
    return view('end');
});
Route::post('/end', 'Auth\AuthController@getLogout');