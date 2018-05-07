@extends('workspace.layouts.main')
@section('main-content')

<h1>Login</h1>
<p> Please log in with the username and password provided to you by your study facilitator. </p>
{{--<p> Support for a Coagmento username is being dropped in favor of emails. If you only have a username, we recommend you add your email address to your profile after logging in.</p>--}}
@include('helpers.showAllMessages')
<div class='col-sm-5'>
    <form method="POST" action="/auth/login">
        {!! csrf_field() !!}
        <div class="form-group">
            <label class="sr-only" for="email">Email</label>
            <input class='form-control' type="text" id="email" name="email" maxlength="255" placeholder="Email or Username" value="{{ strpos(Input::old('email'), 'coagmento.org') !== false ? '' : Input::old('email') }}"/>
        </div>

        <div class="form-group">
            <label class="sr-only" for="password">Password</label>
            <input class='form-control' type="password" id="password" name="password" maxlength="255" placeholder="Password"/>
        </div>

        {{--<div class="form-group">--}}
            {{--<input {{ (null == Input::old('remember')) ? '' : 'checked'}} type="checkbox" id="remember_me" name="remember">--}}
            {{--<label for="remember_me">Remember me</label>--}}
        {{--</div>--}}

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                Login<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
            </button>
        </div>
    </form>
    <p>Don't have an account yet? <a href='/auth/register'>Register here</a>.</p>
</div>
{{--<div class='col-sm-5'>--}}
    {{--<p>Don't want to create an account?</p>--}}
    {{--<form method="POST" action="/auth/demoLogin">--}}
        {{--<div class="form-group">--}}
            {{--<button class="btn btn-primary" type="submit">Continue as Demo User <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></button>--}}
        {{--</div>--}}
        {{--@if (Session::has('after_login_redirect'))--}}
        {{--<input type='hidden' name='after_login_redirect' value='{{ Session::get("after_login_redirect") }}' />--}}
        {{--@endif--}}
    {{--</form>--}}
{{--</div>--}}

@endsection('main-content')