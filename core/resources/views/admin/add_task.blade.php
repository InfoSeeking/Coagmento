@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add a Task
            </div>
            <div class="panel-body">
                <form method="post" action="/admin/manage_tasks">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}

                </form>
            </div>
        </div>
    </div>
@stop