@extends('workspace.layouts.main')
@section('main-content')
<div class="col-md-4">
    <h1>Register</h1>
    @include('helpers.showAllMessages')
    <form method="POST" action="/auth/register">
        {!! csrf_field() !!}

        @if(session('registration_confirmed'))

            <div>
                Confirmed! You have been sent an e-mail.
            </div>

        @else
            <div>
                You must register! <a href='/auth/consent'>Click here</a> to read the consent form and begin registration.
            </div>
        @endif

    </form>
</div>
@endsection('main-content')