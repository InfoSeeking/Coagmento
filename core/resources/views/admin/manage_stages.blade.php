@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">

            <div class="panel-heading">
                Manage Questionnaires
            </div>

            <div class="panel-body">
                <ul class="list-group">
                    @foreach($questionnaires as $questionnaire)
                        <li class="list-group-item">
                            <form action="/admin/{{ $stage->id }}/delete_stage" method="post" style="display: inline-block">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <input type="hidden" name="_method" value="delete" />
                                <button type="submit" onclick="return confirmChoice()" class="btn btn-danger btn btn-sm fa fa-times"></button>
                            </form>

                            <a class="btn btn-link btn-sm" href="/admin/{{ $stage->id }}/edit_questionnaire"  style="display: inline-block;">Edit</a>
                            <a class="btn btn-link btn-sm" href="/admin/{{ $stage->id }}/preview_questionnaire"  style="display: inline-block;">Preview</a>
                            <p style="display: inline-block">{{ $stage->title }}</p>
                        </li>
                    @endforeach
                </ul>
                <a href="/admin/create_stage" class="btn btn-success">Create a Stage</a>
            </div>
        </div>
        <script>
            function confirmChoice(){
                return confirm("Are you sure you want to delete this questionnaire?");
            }
        </script>
    </div>
@stop