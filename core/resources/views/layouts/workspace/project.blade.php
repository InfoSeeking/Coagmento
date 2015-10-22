@extends('layouts.workspace.main')

@section('content')
<div class='row'>
    <div class='col-md-2 col-sm-4 sidebar'>
    <ul>
        <li><a href='/workspace/projects/create' class='link-new-project'><span class='fa fa-plus-square-o'></span> New Project<div class='highlight'></div></a></li>
        <li><a href='/workspace/projects' class='link-my-projects'><span class='fa fa-user'></span> My Projects<div class='highlight'></div></a></li>
        <li><a href='/workspace/projects/sharedWithMe' class='link-shared-projects'><span class='fa fa-users'></span> Shared with me<div class='highlight'></div></a></li>
    </ul>
    </div>
    <div class='col-md-10 col-sm-8 main-content'>
    @yield('page-content')
    </div>
</div>
@endsection('content')