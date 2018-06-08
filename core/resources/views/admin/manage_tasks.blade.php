@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Manage Tasks
            </div>
            <div class="panel-body">
                <a class="btn btn-link" href="add_task">Create a Task</a>
            </div>
        </div>
    </div>
@stop