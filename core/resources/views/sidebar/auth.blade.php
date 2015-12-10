@extends('sidebar.layout')

@section('content')
<div class="col-lg-12">
	<h1 style="font-size:16px"> Log in to Coagmento</h1>
</div>
<div class="col-md-12">
    @include('helpers.showAllMessages')
	<form method="POST" action="/sidebar/auth/login">
        {!! csrf_field() !!}
        <div class="form-group">
            <label class="sr-only" for="email">Email</label>
            <input class='form-control' type="email" id="email" name="email" maxlength="255" placeholder="Email" value="{{ Input::old('email') }}"/>
        </div>

        <div class="form-group">
            <label class="sr-only" for="password">Password</label>
            <input class='form-control' type="password" id="password" name="password" maxlength="255" placeholder="Password"/>
        </div>

        <div class="form-group">
            <input {{ (null == Input::old('remember')) ? '' : 'checked'}} type="checkbox" id="remember_me" name="remember">
            <label for="remember_me">Remember me</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                Login <span class="fa fa-arrow-circle-o-right" aria-hidden="true"></span>
            </button>
        </div>
    </form>
</div>

<div class='col-md-12'>
    <p>Don't want to create an account?</p>
    <form method="POST" action="/sidebar/auth/demoLogin">
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Continue as Demo User <span class="fa fa-arrow-circle-o-right" aria-hidden="true"></span></button>
        </div>
    </form>
</div>
@endsection('content')