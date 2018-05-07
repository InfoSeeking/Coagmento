@extends('workspace.layouts.main')
@section('main-content')
<div class="col-md-4">
    <h1>Register</h1>
    @include('helpers.showAllMessages')
    <form method="POST" action="/auth/register">
        {!! csrf_field() !!}

        @if((session('consent_datacollection') and session('consent_audio')) or count($errors) > 0)


            <div class="form-group">
                <div>Age: {!! Form::text('age') !!}</div>
            </div><br>

            <div class="form-group">
                <div>Gender: {!! Form::select('gender', array(
    'Male' => array('male' => 'Male'),
    'Female' => array('female' => 'Female'),
)); !!}</div>
            </div><br>

            <div class="form-group">
                <div>Major: {!! Form::text('major') !!}</div>
            </div><br>

            <div class="form-group">
                <div>Is English your native language?
                    <div class="radio">
                        <label>{!! Form::radio('english_first','Yes') !!}Yes</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('english_first','No') !!}No</label>
                    </div>
                </div>
            </div><br>


            <div class="form-group">
                <div>(<strong>If not</strong>) please provide your native language: {!! Form::text('native_language') !!}</div>
            </div><br>

            <div class="form-group">
                <div>How many years have you spent searching the web?
                    {!! Form::text('search_experience') !!}
                </div>
            </div><br>

            <div class="form-group">
                <div>Approximately how much time do you spend searching the web each day?
                    <div class="radio">
                        <label>{!! Form::radio('search_frequency','<0.5 hour') !!}<0.5 hour</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('search_frequency','>=0.5 hour & <1 hour') !!}>=0.5 hour & <1 hour</label>
                    </div>

                    <div class="radio">
                        <label>{!! Form::radio('search_frequency','>= 1 hour & <1.5 hour') !!}>= 1 hour & <1.5 hour</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('search_frequency','>=1.5 hour & <2 hour') !!}>=1.5 hour & <2 hour</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('search_frequency','>=2 hour & < 2.5 hour') !!}>=2 hour & < 2.5 hour</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('search_frequency','>=2.5 hour & <3 hour') !!}>=2.5 hour & <3 hour</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('search_frequency','>=3 hour') !!}>=3 hour</label>
                    </div>
                </div>
            </div><br>

            <div class="form-group">
                <div>About how often do you engage in non-search-engine-related information seeking practices per day (e.g., call a friend for information, email TA or professor, face-to-face meeting and asking questions to a friend or family member)?
                    <div class="radio">
                        <label>{!! Form::radio('nonsearch_frequency','no more than 1 time') !!}no more than 1 time</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('nonsearch_frequency','2-5 times') !!}2-5 times</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('nonsearch_frequency','5-10 times') !!}5-10 times</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('nonsearch_frequency','11-15 times') !!}11-15 times</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('nonsearch_frequency','16-20 times') !!}16-20 times</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('nonsearch_frequency','21-25 times') !!}21-25 times</label>
                    </div>
                    <div class="radio">
                        <label>{!! Form::radio('nonsearch_frequency','more than 25 times') !!}more than 25 times</label>
                    </div>
                </div>
            </div><br>
            <hr/>

            <div class="form-group">
                <label class="sr-only" for="name">Full Name</label>
                <input class='form-control' type="text" id="name" name="name" maxlength="255" placeholder="Full Name" value="{{ Input::old('name') }}"/>
            </div>

            <div class="form-group">
                <label class="sr-only" for="email">Email</label>
                <input class='form-control' type="email" id="email" name="email" maxlength="255" placeholder="Email" value="{{ Input::old('email') }}"/>
                <small>This email will only be contacted for important alerts, and will not be used for advertising of any sort.</small>
            </div>



            {{--<div class="form-group">--}}
                {{--<label class="sr-only" for="password">Password</label>--}}
                {{--<input class='form-control' type="password" id="password" name="password" maxlength="255" placeholder="Password"/>--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
                {{--<label class="sr-only" for="password_confirmation">Confirm Password</label>--}}
                {{--<input class='form-control' type="password" id="password_confirmation" name="password_confirmation" maxlength="255" placeholder="Confirm Password"/>--}}
            {{--</div>--}}

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Register<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </button>
            </div>

            {!! Form::hidden('consent_datacollection',session('consent_datacollection')) !!}
            {!! Form::hidden('consent_audio',session('consent_audio')) !!}
            @if(session('consent_furtheruse'))
                {!! Form::hidden('consent_furtheruse',session('consent_furtheruse')) !!}
            @else
                {!! Form::hidden('consent_furtheruse',0) !!}
            @endif

        @else
            <div>
                You must sign the consent form to register! <a href='/auth/consent'>Click here</a> to read the consent form.
            </div>
        @endif

    </form>
</div>
@endsection('main-content')