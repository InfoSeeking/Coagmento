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

// Splash page.
Route::get('/', 'SplashController@index');
Route::get('/new', 'SplashController@index');
Route::post('/new/notify', 'SplashController@notify');
Route::get('/confirm', function(){
    return view('auth.confirm');
});





// Authentication.
Route::get('auth/loggedin',function(){
    return Auth::user();
});
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLoginWithOldCoagmentoSupport');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('auth/confirmation', 'Auth\AuthController@getConfirmation');
Route::post('auth/demoLogin', 'Auth\AuthController@demoLogin');
Route::get('auth/studywelcome', 'Auth\AuthController@getStudyWelcome');
Route::post('auth/studywelcome', 'Auth\AuthController@postStudyWelcome');
Route::get('auth/consent', 'Auth\AuthController@getConsent');
Route::post('auth/consent', 'Auth\AuthController@postConsent');


// Sidebar pages.
Route::group(['middleware' => 'sidebar.auth'], function() {
	Route::get('sidebar', 'SidebarController@getProjectSelection');
	Route::get('sidebar/project/{project_id}', 'SidebarController@getFeed');
    Route::get('sidebar/project/{project_id}', 'SidebarController@getFeed');

    Route::post('sidebar/keystrokes', 'Api\KeystrokeController@storeMany');
    Route::post('sidebar/clicks', 'Api\ClickController@storeMany');
    Route::post('sidebar/actions', 'Api\ActionController@store');
    Route::post('sidebar/scrolls', 'Api\ScrollActionController@storeMany');
    Route::post('sidebar/copies', 'Api\CopyActionController@storeMany');
    Route::post('sidebar/pastes', 'Api\PasteActionController@storeMany');
    Route::post('sidebar/mouseactions', 'Api\MouseActionController@storeMany');
});

Route::get('sidebar/auth/login', 'SidebarController@getSidebarLogin');
Route::post('sidebar/auth/login', 'SidebarController@postLoginSidebar');
//Route::post('sidebar/auth/login', 'SidebarController@postLoginWithOldCoagmentoSupport');
Route::get('sidebar/auth/logout', 'SidebarController@getLogoutSidebar');
Route::post('sidebar/auth/demoLogin', 'SidebarController@demoLogin');



// Workspace pages.
Route::group(['middleware' => ['auth','stage']], function() {
    Route::get('/stages', 'StageProgressController@directToStage');

    Route::get('/stages/next', 'StageProgressController@moveToNextStage');
	// These pages do not make sense without a logged in user.
//	Route::get('workspace', 'WorkspaceController@viewPanel');
//	Route::get('workspace/projects', 'WorkspaceController@showProjects');
//	Route::get('workspace/projects/create', 'WorkspaceController@showProjectCreate');
//	Route::get('workspace/projects/sharedWithMe', 'WorkspaceController@showShared');
//	Route::post('workspace/projects/create', 'WorkspaceController@createProject');
//	Route::get('workspace/user/settings', 'WorkspaceController@showUserSettings');
//	Route::post('workspace/user/settings', 'WorkspaceController@updateUserSettings');


    //Stages
    Route::get('/welcome', function () {
        return view('welcome');
    });
    Route::post('/welcome', 'StageProgressController@moveToNextStage');


    Route::get('/questionnaire_pretask', 'QuestionnaireController@getPretask');
    Route::post('/questionnaire_pretask', 'QuestionnaireController@postPretask');

    Route::get('/task_description', 'TaskController@getTaskDescription');
    Route::post('/task_description', 'StageProgressController@moveToNextStage');

    Route::get('/task', 'TaskController@getTask');
    Route::post('/task', 'StageProgressController@moveToNextStage');

    Route::get('/questionnaire_posttask', function () {
        return view('questionnaire_posttask');
    });
    Route::post('/questionnaire_posttask','QuestionnaireController@postPosttask');

    Route::get('/end', function(){
        $user = Auth::user();
        $user->is_completed = true;
        $user->save();
        return view('end');
    });
    Route::post('/end', 'Auth\AuthController@getLogout');


});

//Admin Page(s)
Route::get('/admin','AdminController@index');
    //User Management
Route::get('/admin/manage_users', 'AdminController@manageUsers');
Route::post('/admin/manage_users', 'AdminController@addUser');
Route::get('/admin/{user}/edit_user', 'AdminController@editUser');
Route::get('/admin/{user}/send', 'AdminController@sendCredentials');
Route::patch('/admin/{user}/edit_user', 'AdminController@update');
Route::delete('/admin/{user}/delete','AdminController@delete');
    //Task Management
Route::get('/admin/manage_tasks', 'AdminController@manageTasks');
Route::get('/admin/add_task','AdminController@newTask');
Route::post('/admin/manage_tasks', 'TaskController@addTask');
Route::get('/admin/{task}/edit_task', 'TaskController@editTask');
Route::patch('admin/{task}/edit_task', 'TaskController@update');
Route::delete('/admin/{task}/delete_task','TaskController@destroy');
    //Attributes
Route::get('/admin/task_settings','AdminController@viewTaskSettings');
Route::post('/admin/task_settings', 'AttributeController@store');
Route::get('/admin/{task}/edit_attribute', 'AttributeController@edit');
Route::patch('/admin/{attribute}/update_attribute', 'AttributeController@update');
Route::delete('/admin/{attribute}/delete_attribute', 'AttributeController@destroy');
    //Emails
Route::get('/admin/manage_emails', 'EmailController@listEmails');
Route::get('/admin/create_email', 'EmailController@newEmail');
Route::post('/admin/create_email', 'EmailController@createEmail');
Route::delete('/admin/{email}/delete_email', 'EmailController@destroy');
Route::get('/admin/{email}/edit_email', 'EmailController@edit');
Route::patch('/admin/{email}/edit_email', 'EmailController@update');
    //Questionnaires
Route::get('/admin/manage_questionnaires','QuestionnaireController@manageQuestionnaires');
Route::get('/admin/create_questionnaire','QuestionnaireController@create');
Route::post('/admin/create_questionnaire', 'QuestionnaireController@store');
Route::get('/admin/{questionnaire}/edit_questionnaire', 'QuestionnaireController@edit');
Route::patch('/admin/{questionnaire}/edit_questionnaire', 'QuestionnaireController@update');
Route::delete('/admin/{questionnaire}/delete_questionnaire', 'QuestionnaireController@destroy');
Route::get('/admin/{questionnaire}/preview_questionnaire', 'QuestionnaireController@preview');
    //Stages
Route::get('/admin/manage_stages', 'StageController@index');
Route::post('/admin/manage_stages', 'StageController@stageOrder');
Route::get('/admin/create_stage', 'StageController@create');
Route::post('/admin/create_stage', 'StageController@store');
Route::delete('/admin/{stage}/delete_stage', 'StageController@destroy');
Route::get('/admin/{stage}/edit_stage', 'StageController@edit');
Route::patch('/admin/{stage}/edit_stage', 'StageController@update');
Route::get('/admin/{stage}/preview_stage', 'StageController@preview');
Route::post('/admin/create_widget', 'StageController@createWidget');



//Route::get('workspace/projects/{project_id}/bookmarks/{bookmark_id}',
//	'WorkspaceController@viewBookmark');
//Route::get('workspace/projects/{project_id}', 'WorkspaceController@viewProject');
//Route::get('workspace/projects/{project_id}/bookmarks',
//	'WorkspaceController@viewProjectBookmarks');
//Route::get('workspace/projects/{project_id}/snippets', 'WorkspaceController@viewProjectSnippets');
//Route::get('workspace/projects/{project_id}/chat', 'WorkspaceController@viewChat');
//Route::get('workspace/projects/{project_id}/docs', 'WorkspaceController@viewDocs');
//Route::get('workspace/projects/{project_id}/history', 'WorkspaceController@viewHistory');
//
//// Viewing document requires write permissions until we can get read-only to work.
//Route::get('workspace/projects/{project_id}/docs/{doc_id}', 'WorkspaceController@viewDoc');
//
//Route::delete('workspace/projects/{project_id}', 'WorkspaceController@deleteProject');
//Route::get('workspace/projects/{project_id}/settings', 'WorkspaceController@viewProjectSettings');

// API.
Route::group(['middleware' => 'api.auth'], function() {
	// These endpoints do not make sense without a logged in user.
	Route::get('api/v1/users/current', 'Api\UserController@getCurrent');
    Route::get('api/v1/currentproject', 'StageProgressController@getCurrentProject');
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

    Route::post('api/v1/queryquestionnaire', 'QuestionnaireController@postQuerySegmentQuestionnaire');

    Route::get('api/v1/stages/current', 'StageProgressController@getCurrentStageUser');

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

    Route::post('api/v1/pagesqueries', 'Api\PageController@createPageOrQuery');

	// Chat.
	Route::post('api/v1/chatMessages', 'Api\ChatController@create');
	Route::get('api/v1/chatMessages', 'Api\ChatController@getMultiple');

	// Docs.
	Route::post('api/v1/docs', 'Api\DocController@create');
	Route::get('api/v1/docs', 'Api\DocController@getMultiple');
	Route::delete('api/v1/docs/{doc_id}', 'Api\DocController@delete');
	Route::get('api/v1/docs/{doc_id}/text', 'Api\DocController@getText');
});




