@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Manage Users</div>
            <div class="panel-body">

                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">
                            Users and Users Settings
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="add-tab" data-toggle="tab" href="#add" role="tab" aria-controls="add" aria-selected="false">
                            Add Users
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="remove-tab" data-toggle="tab" href="#remove" role="tab" aria-controls="remove" aria-selected="false">
                            Remove Users
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <br>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Admin</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user as $user)
                            <tr>
                                <th>There is</th>
                                <th>no</th>
                                <th>users</th>
                                <th>created.</th>
                            </tr>
                            </tbody>
                        </table>

                        <form method="POST" action="/admin/manage_users">

                            {{ csrf_field() }}
                            {{ method_field('POST') }}

                            <div class="form-group">
                                <button type="submit" class="btn btn-default">Add User</button>
                            </div>
                        </form>

                        <p>As users are added, this tab should display a table with users, user information, delete option, maybe more?
                        Eventually instead of having a remove users tab, merge it to here and have a button. The button should have a confirmation to delete.
                        Also add user probably does not need to be separated unless forms involved.
                        </p>

                    </div>
                    <div class="tab-pane" id="add" role="tabpanel" aria-labelledby="add-tab">
                        Add a Random User
                    </div>
                    <div class="tab-pane" id="remove" role="tabpanel" aria-labelledby="remove-tab">
                        List all users and remove.
                        Eventually merge
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop