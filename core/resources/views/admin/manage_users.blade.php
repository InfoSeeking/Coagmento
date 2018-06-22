@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Manage Users</div>
            <div class="panel-body">
                <form method="POST" action="/admin/manage_users">

                    {{ csrf_field() }}
                    {{ method_field('POST') }}

                    <div class="form-group">
                        <button type="submit" class="btn btn-default">Add User</button>
                    </div>
                </form>

                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>Options</th>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Admin</th>
                        <th>Active</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        @if($user!=Auth::user())

                            <tr>
                                <th>
                                    <form action="/admin/{{ $user->id }}/delete" method="delete">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input type="hidden" name="_method" value="delete" />
                                        <button type="submit" onclick="return confirmChoice()" class="btn btn-danger btn btn-sm"> <span class="fa fa-times"></span> </button>
                                    </form>
                                    <script>
                                        function confirmChoice(){
                                            return confirm("Are you sure you want to delete this user?");
                                        }
                                    </script>
                                </th>
                                <th>
                                    <a class="btn btn-link btn btn-sm" href="/admin/{{ $user->id }}/edit_user">Edit</a>
                                </th>

                                <th>
                                    {{ $user->name }}
                                </th>

                                <th>{{ $user->email }}</th>

                                <th>{{ $user->password_raw }}</th>

                                <th>
                                    @if($user->is_admin)
                                        <b>True</b>
                                    @else
                                        <b>False</b>
                                    @endif
                                </th>

                                <th>
                                    @if($user->active)
                                        <b>True</b>
                                    @else
                                        <b>False</b>
                                    @endif
                                </th>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>

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