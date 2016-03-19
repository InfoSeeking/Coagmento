@extends('workspace.layouts.main')

@section('sidebar')
<ul>
	<li><a href='/workspace/projects' class='link-my-projects'><span class='fa fa-user'></span> My Projects<div class='highlight'></div></a></li>
    <li><a href='/workspace/projects/create' class='link-new-project'><span class='fa fa-plus-square-o'></span> New Project<div class='highlight'></div></a></li>
    <li><a href='/workspace/projects/sharedWithMe' class='link-shared-projects'><span class='fa fa-users'></span> Shared with me<div class='highlight'></div></a></li>
    <li><a href='/workspace/user/settings' class='link-user-settings'><span class='fa fa-user'></span> User Settings<div class='highlight'></div></a></li>
</ul>
@endsection('sidebar')