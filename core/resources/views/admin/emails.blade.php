@extends('admin.layout')
@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                Manage Emails
            </div>
            <div class="panel panel-body">
                <table class="table table-condensed table-hover">
                    <thead class="label-info">
                    <tr>
                        <th></th>
                        <th>Subject</th>
                        <th>Body</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($emails as $email)
                        <tr>
                            <th>
                                <form action="/admin/{{ $email->id }}/delete_email" id="delete">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <span{{--class="form-group"--}}>
                                    <a type="submit" onclick="return confirmChoice()" class="btn btn-danger btn btn-sm" style="float: left" href="/admin/{{ $email->id }}/delete_email">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </a>
                                    </span>
                                </form>
                            </th>
                            <th>{{$email->subject}}</th>
                            <th>{{$email->body}}</th>
                            <th>
                                <a class="btn btn-warning btn-sm" href="/admin/{{$email->id}}/edit_email"><span class="glyphicon glyphicon-pencil"></span> </a>
                            </th>
                        </tr>
                    @endforeach
                    <script>
                        function confirmChoice(){
                            return confirm("Are you sure you want to delete this email?");
                        }
                    </script>
                    </tbody>
                </table><br>
                <a href="create_email" class="btn btn-success">Create a New Option</a>

            </div>
        </div>
    </div>

@stop