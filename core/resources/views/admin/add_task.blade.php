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
                <form method="POST" action="/admin/manage_tasks">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}

                    <div class="form-group">
                        <div>
                            Description: <br>
                            <!-- make this form bigger
                            {!! Form::text('description') !!}-->
                            <textarea rows="4" name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            Product:<br>
                            {!! Form::select('product', array(
                                'Factual',
                                'Intellectual',
                                )); !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            Goal:<br>
                            {!! Form::select('goal', array(
                                'Specific',
                                'Amorphous',
                            )); !!}
                        </div>
                    </div>
                    <span>
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="/admin/manage_tasks" class="btn btn-default">Cancel</a>
                    </span>
                </form>

            </div>
        </div>
    </div>
@stop