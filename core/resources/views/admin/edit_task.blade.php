@extends('admin.layout')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit Task
            </div>
            <div class="panel-body">
                <form method="POST" action="/admin/{{ $task->id }}/edit_task">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="form-group">
                        <div>
                            Description: <br>
                        <!-- make this form bigger
                            {!! Form::text('description') !!}-->
                            <textarea rows="4" name="description" class="form-control">{{ $task->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- EVENTUALLY WHEN CLEANING MAKE OLD CHOICES PREVIOUSLY SELECTED -->
                        <div>
                            Product: {!! Form::select('product', array(
                                'Factual',
                                'Intellectual',
                                )); !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            Goal: {!! Form::select('goal', array(
                                'Specific',
                                'Amorphous',
                                )); !!}
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/manage_tasks" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@stop