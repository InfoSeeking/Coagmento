@extends('layouts.workspace.main')
@section('content')
<div class="container">
    <div class="row">
        <h1>Register for a New Account</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('helpers.showAllMessages')
            <form method="POST" action="/auth/register">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label class="sr-only" for="name">Full Name</label>
                    <input type="text" id="name" name="name" maxlength="255" placeholder="Full Name" value="{{ Input::old('name') }}"/>
                </div>

                <div class="form-group">
                    <label class="sr-only" for="email">Email</label>
                    <input type="email" id="email" name="email" maxlength="255" placeholder="Email" value="{{ Input::old('email') }}"/>
                    <small>This email will only be contacted for important alerts, and will not be used for advertising of any sort.</small>
                </div>

                <div class="form-group">
                    <label class="sr-only" for="password">Password</label>
                    <input type="password" id="password" name="password" maxlength="255" placeholder="Password"/>
                </div>

                <div class="form-group">
                    <label class="sr-only" for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" maxlength="255" placeholder="Confirm Password"/>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        Register<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
            <p>Already have an account? <a href='/auth/login'>Login here</a>.</p>
        </div>
    </div>
</div>
@endsection('content')