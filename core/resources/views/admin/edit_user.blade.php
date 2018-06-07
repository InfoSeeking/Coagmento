@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')

    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Change this user's settings:</div>
            <div class="panel-body">
                <br>

                <form method="POST" action="/admin/{{ $user->id }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>Traits</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th> Name </th>
                                <th> {{ $user->name }}</th>
                            </tr>
                            <tr>
                                <th> Email </th>
                                <th> {{ $user->email }}</th>
                            </tr>
                            <tr>
                                <th> Active </th>
                                <th>
                                    <div class="form-check">
                                        @if($user->active)
                                            <input name="active" value="active"  method="POST" action="{{ url('admin/{user}/edit_user').$user->active }}" type="checkbox" class="form-check-input" id="active" checked>
                                        @else
                                            <input name="active" value="active"  method="POST" action="{{ url('admin/{user}/edit_user').$user->active }}" type="checkbox" class="form-check-input" id="active">
                                        @endif
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th>Admin</th>
                                <th>
                                    <div class="form-check">
                                        @if($user->admin==1)
                                            <input name="admin" value="admin"  method="POST" action="{{ url('admin/{user}/edit_user').$user->admin }}" type="checkbox" class="form-check-input" id="admin" checked>
                                        @else
                                            <input name="admin" value="admin"  method="POST" action="{{ url('admin/{user}/edit_user').$user->admin }}" type="checkbox" class="form-check-input" id="admin">
                                        @endif
                                    </div>
                                </th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="form-group">
                        <button type="submit" class="btn btn-default">Update</button>
                        <a class="btn btn-default" href="/admin/manage_users">Return</a>
                    </div>
                </form>

            </div>
            @if(count($errors))
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{error}}</li>
                    @endforeach
                </ul>
            @endif
        <!--{{ var_dump($errors) }}-->
        </div>
@stop