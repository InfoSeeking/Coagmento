@extends('layouts.workspace.main')

@section('navigation')
@endsection('navigation')

@section('content')

<div class="row">
    <div class='col-md-2 col-sm-4 sidebar'>
    <ul>
        <li><a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects<div class='highlight'></div></a></li>
    </ul>
    </div>
    <div class='col-md-10 col-sm-8 main-content'>
    <h2>Welcome to Coagmento 2.0</h2>
	<p>
		This is your new Coagmento workspace. Here you can easily manage and share your projects and see updates on projects you follow.
	</p>
    </div>
</div>


@endsection('content')