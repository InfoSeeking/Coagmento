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
        <div class="col-md-6">
            @include('helpers.showAllMessages')
            <h2>Welcome to Coagmento 3.0</h2>
             <p>
                 This is your new Coagmento workspace. You can manage your projects, share and collaborate with others, view and edit saved project data, and view analytics on your projects.
             </p>
             <p> To get started, click <a href="/workspace/projects">Projects</a>!</p>
             <p>
                Coagmento is still under active development, but you can follow the development on our <a href="https://github.com/InfoSeeking/Coagmento" target="_blank">GitHub page</a>.
             </p>
        </div> 
    </div>
</div>


@endsection('content')