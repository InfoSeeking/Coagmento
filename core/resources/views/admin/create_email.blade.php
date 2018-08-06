@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Create a New Email</div>
            <div class="panel-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="/admin/create_email">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}

                    <div class="form-group subject">
                        Subject:<br>
                        <input class="form-control" id="subject" name="subject">
                    </div>

                    <div class="form-group">
                        <div>
                            Body: <br>
                            <textarea rows="4" name="body" id="body" class="form-control"></textarea>
                        </div>
                    </div>

                    <span>
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="/admin/manage_emails" class="btn btn-default">Cancel</a>
                    </span>
                </form>

            </div>
        </div>
    </div>


@stop