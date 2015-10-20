@extends('layouts.workspace.main')
@section('content')
<div class="container">
    <div class="row">
        <h1>Login</h1>
    <div>
    <div class="row">
        
        <div class="col-md-12">
            @include('helpers.showAllMessages')
            <p>Don't want to create an account?</p>
            <form method="POST" action="/workspace/demoLogin">
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Continue as Demo User <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></button>
                </div>
            </form>

            <form method="POST" action="/auth/login">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label class="sr-only" for="email">Email</label>
                    <input type="email" id="email" name="email" maxlength="255" placeholder="Email" value="{{ Input::old('email') }}"/>
                </div>

                <div class="form-group">
                    <label class="sr-only" for="password">Password</label>
                    <input type="password" id="password" name="password" maxlength="255" placeholder="Password"/>
                </div>

                <div class="form-group">
                    <input {{ (null == Input::old('remember')) ? '' : 'checked'}} type="checkbox" id="remember_me" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        Login<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
            <p>Don't have an account yet? <a href='/auth/register'>Register here</a>.</p>
        </div>
    </div>
</div>
@endsection('content')