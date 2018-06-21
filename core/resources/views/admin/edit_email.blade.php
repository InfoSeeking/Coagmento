@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
    <script src="jquery-3.3.1.min.js"></script>
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel panel-heading">Edit Email</div>
            <div class="panel panel-body">
                <form method="POST" action="/admin/{{$email->id}}/edit_email">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="form-group subject">
                        Subject:<br>
                        <input class="form-control" id="subject" name="subject" value="{{$email->subject}}">
                    </div>

                    <div class="form-group">
                        <div>
                            Body: <br>
                            <textarea rows="4" name="body" id="body" class="form-control">{{$email->body}}</textarea>
                        </div>
                    </div>

                    <span>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="/admin/manage_emails" class="btn btn-default">Cancel</a>
                    </span>
                </form>

            </div>
        </div>
    </div>


@stop