@extends('layouts.workspace.main')

@section('content')
<div class='row'>
    <div class='col-md-2 col-sm-4 sidebar'>
    @yield('context')
    <ul>
        <li><a href='/workspace/projects/{{ $project->id }}' class='active'><span class='fa fa-line-chart'></span> Activity<div class='highlight'></div></a></li>
        <li><a href='/workspace/projects/{{ $project->id }}/bookmarks' class='active'><span class='fa fa-star-o'></span> Bookmarks<div class='highlight'></div></a></li>
        <li><a href='/workspace/projects/{{ $project->id }}/snippets' class='active'><span class='fa fa-sticky-note-o'></span> Snippets<div class='highlight'></div></a></li>
        <li><a><span class='fa fa-thumbs-o-up'></span> More coming soon!</a></li>
    </ul>
    </div>
    <div class='col-md-10 col-sm-8 main-content'>
    @yield('page-content')
    </div>
</div>
@endsection('content')