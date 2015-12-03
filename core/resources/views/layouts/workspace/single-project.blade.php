@extends('layouts.workspace.main')

@section('content')
<div class='row'>
    <div class='col-md-2 col-sm-4 sidebar'>
    <ul>
        <li><a href='/workspace/projects/{{ $project->id }}' class='link-activity'><span class='fa fa-line-chart'></span> Activity<div class='highlight'></div></a></li>
        <li><a href='/workspace/projects/{{ $project->id }}/bookmarks' class='link-bookmarks'><span class='fa fa-star-o'></span> Bookmarks<div class='highlight'></div></a></li>
        <li><a href='/workspace/projects/{{ $project->id }}/snippets' class='link-snippets'><span class='fa fa-sticky-note-o'></span> Snippets<div class='highlight'></div></a></li>
        <li><a href='/workspace/projects/{{ $project->id }}/chat' class='link-chat'><span class='fa fa-comment'></span> Group Chat<div class='highlight'></div></a></li>
        <li><a href='/workspace/projects/{{ $project->id }}/docs' class='link-docs'><span class='fa fa-file-text-o'></span> Documents<div class='highlight'></div></a></li>
    </ul>
    @yield('context')
    </div>
    <div class='col-md-10 col-sm-8 main-content'>
    @yield('page-content')
    </div>
</div>
@endsection('content')