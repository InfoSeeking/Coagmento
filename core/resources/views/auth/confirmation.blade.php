@extends('workspace.layouts.main')
@section('main-content')
<div class="col-md-4">
    <h1>Register</h1>
    @include('helpers.showAllMessages')
    <form method="POST" action="/auth/register">
        {!! csrf_field() !!}

        @if(session('registration_confirmed'))

            <div>
                <p>Thank you for registering for our study!  A confirmation e-mail has been sent to the address you provided.  <strong>Please keep track of your scheduled study date, and save the e-mail for your records.</strong></p>
                <p>If you have any questions, please send e-mail to Jiqun Liu at
                    <a href="mailto:jl2033@scarletmail.rutgers.edu?subject=Study inquiry">jl2033@scarletmail.rutgers.edu</a> or Shawon Sarkar at <a href="mailto:ss2577@scarletmail.rutgers.edu?subject=Study inquiry">ss2577@scarletmail.rutgers.edu</a>.</p>

                <p>We look forward to seeing you soon!</p>
            </div>

        @else
            <div>
                You must register! <a href='/auth/consent'>Click here</a> to read the consent form and begin registration.
            </div>
        @endif

    </form>
</div>
@endsection('main-content')