@extends('layouts.workspace.main')
@section('content')
<div class='row'>
    <div class='col-md-2 col-sm-4'>
    <ul>
        <li><a href='/workspace/projects/create' class='active'>New Project</a></li>
        <li><a href='/workspace/projects'>My Projects</a></li>
        <li><a href='/workspace/projects/sharedWithMe'>Shared with me</a></li>
    </ul>
    </div>
    <div class='col-md-10 col-sm-8 main-content'>
    @yield('page-content')
    </div>
</div>
@endsection('content')