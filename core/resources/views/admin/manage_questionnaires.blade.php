@extends('admin.layout')

@section('header')

@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                Manage Questionnaires
            </div>

            <div class="panel panel-body">
                <a href="/admin/create_questionnaire" class="btn btn-success">Create a Questionnaire</a>
            </div>

        </div>
    </div>
@stop