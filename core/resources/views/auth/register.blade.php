@extends('workspace.layouts.main')
@section('main-content')



{{--{!! NoCaptcha::renderJs() !!}--}}


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

            <?php

                $all_dates = [

//                    "Tuesday, July 31 9:00 AM - 11:00 AM",
//                    "Tuesday, July 31 11:00 AM - 1:00 PM",
//                    "Tuesday, July 31 1:00 PM - 3:00 PM",
//                    "Tuesday, July 31 3:00 PM - 5:00 PM",
//
//                    "Wednesday, August 1 9:00 AM - 11:00 AM",
//                    "Wednesday, August 1 11:00 AM - 1:00 PM",
//                    "Wednesday, August 1 1:00 PM - 3:00 PM",
//                    "Wednesday, August 1 3:00 PM - 5:00 PM",
//
//                    "Thursday, August 2 9:00 AM - 11:00 AM",
//                    "Thursday, August 2 11:00 AM - 1:00 PM",
//                    "Thursday, August 2 1:00 PM - 3:00 PM",
//                    "Thursday, August 2 3:00 PM - 5:00 PM",
//
//                    "Friday, August 3 9:00 AM - 11:00 AM",
//                    "Friday, August 3 11:00 AM - 1:00 PM",
//                    "Friday, August 3 1:00 PM - 3:00 PM",
//                    "Friday, August 3 3:00 PM - 5:00 PM",
//
//                    "Saturday, August 4 9:00 AM - 11:00 AM",
//                    "Saturday, August 4 11:00 AM - 1:00 PM",
//                    "Saturday, August 4 1:00 PM - 3:00 PM",
//                    "Saturday, August 4 3:00 PM - 5:00 PM",
//
//                    "Monday, August 6 9:00 AM - 11:00 AM",
//                    "Monday, August 6 11:00 AM - 1:00 PM",
//                    "Monday, August 6 1:00 PM - 3:00 PM",
//                    "Monday, August 6 3:00 PM - 5:00 PM",
//
//                    "Tuesday, August 7 9:00 AM - 11:00 AM",
//                    "Tuesday, August 7 11:00 AM - 1:00 PM",
//                    "Tuesday, August 7 1:00 PM - 3:00 PM",
//                    "Tuesday, August 7 3:00 PM - 5:00 PM",
//
//
//                    "Wednesday, August 8 9:00 AM - 11:00 AM",
//                    "Wednesday, August 8 11:00 AM - 1:00 PM",
//                    "Wednesday, August 8 1:00 PM - 3:00 PM",
//                    "Wednesday, August 8 3:00 PM - 5:00 PM",
//
//                    "Thursday, August 9 9:00 AM - 11:00 AM",
//                    "Thursday, August 9 11:00 AM - 1:00 PM",
//                    "Thursday, August 9 1:00 PM - 3:00 PM",
//                    "Thursday, August 9 3:00 PM - 5:00 PM",
//
//                    "Friday, August 10 9:00 AM - 11:00 AM",
//                    "Friday, August 10 11:00 AM - 1:00 PM",
//                    "Friday, August 10 1:00 PM - 3:00 PM",
//                    "Friday, August 10 3:00 PM - 5:00 PM",
//
//                    "Saturday, August 11 9:00 AM - 11:00 AM",
//                    "Saturday, August 11 11:00 AM - 1:00 PM",
//                    "Saturday, August 11 1:00 PM - 3:00 PM",
//                    "Saturday, August 11 3:00 PM - 5:00 PM",
//
//                    "Monday, August 13 9:00 AM - 11:00 AM",
//                    "Monday, August 13 11:00 AM - 1:00 PM",
//                    "Monday, August 13 1:00 PM - 3:00 PM",
//                    "Monday, August 13 3:00 PM - 5:00 PM",
//
//                    "Tuesday, August 14 9:00 AM - 11:00 AM",
//                    "Tuesday, August 14 11:00 AM - 1:00 PM",
//                    "Tuesday, August 14 1:00 PM - 3:00 PM",
//                    "Tuesday, August 14 3:00 PM - 5:00 PM",
//
//
//                    "Wednesday, August 15 9:00 AM - 11:00 AM",
//                    "Wednesday, August 15 11:00 AM - 1:00 PM",
//                    "Wednesday, August 15 1:00 PM - 3:00 PM",
//                    "Wednesday, August 15 3:00 PM - 5:00 PM",
//
//                    "Thursday, August 16 9:00 AM - 11:00 AM",
//                    "Thursday, August 16 11:00 AM - 1:00 PM",
//                    "Thursday, August 16 1:00 PM - 3:00 PM",
//                    "Thursday, August 16 3:00 PM - 5:00 PM",

//                    "Friday, August 17 11:00 AM - 1:00 PM",
//                    "Friday, August 17 1:00 PM - 3:00 PM",
//                    "Friday, August 17 3:00 PM - 5:00 PM",
//
//                    "Saturday, August 18 11:00 AM - 1:00 PM",
//                    "Saturday, August 18 1:00 PM - 3:00 PM",
//                    "Saturday, August 18 3:00 PM - 5:00 PM",
//
//                    "Monday, August 20 11:00 AM - 1:00 PM",
//                    "Monday, August 20 1:00 PM - 3:00 PM",
//                    "Monday, August 20 3:00 PM - 5:00 PM",
//
//
//                    "Tuesday, August 21 11:00 AM - 1:00 PM",
//                    "Tuesday, August 21 1:00 PM - 3:00 PM",
//                    "Tuesday, August 21 3:00 PM - 5:00 PM",
//
//
//                    "Wednesday, August 22 11:00 AM - 1:00 PM",
//                    "Wednesday, August 22 1:00 PM - 3:00 PM",
//                    "Wednesday, August 22 3:00 PM - 5:00 PM",
//
//                    "Thursday, August 23 11:00 AM - 1:00 PM",
//                    "Thursday, August 23 1:00 PM - 3:00 PM",
//                    "Thursday, August 23 3:00 PM - 5:00 PM",
//
//                    "Friday, August 24 11:00 AM - 1:00 PM",
//                    "Friday, August 24 1:00 PM - 3:00 PM",
//                    "Friday, August 24 3:00 PM - 5:00 PM",
//
//                    "Saturday, August 25 11:00 AM - 1:00 PM",
//                    "Saturday, August 25 1:00 PM - 3:00 PM",
//                    "Saturday, August 25 3:00 PM - 5:00 PM",
//
//
//                    "Monday, August 27 11:00 AM - 1:00 PM",
//                    "Monday, August 27 1:00 PM - 3:00 PM",
//                    "Monday, August 27 3:00 PM - 5:00 PM",
//
//                    "Tuesday, August 28 11:00 AM - 1:00 PM",
//                    "Tuesday, August 28 1:00 PM - 3:00 PM",
//                    "Tuesday, August 28 3:00 PM - 5:00 PM",
//
//
//                    "Wednesday, August 29 11:00 AM - 1:00 PM",
//                    "Wednesday, August 29 1:00 PM - 3:00 PM",
//                    "Wednesday, August 29 3:00 PM - 5:00 PM",
//
//                    "Thursday, August 30 11:00 AM - 1:00 PM",
//                    "Thursday, August 30 1:00 PM - 3:00 PM",
//                    "Thursday, August 30 3:00 PM - 5:00 PM",
//
//                    "Friday, August 31 11:00 AM - 1:00 PM",
//                    "Friday, August 31 1:00 PM - 3:00 PM",
//                    "Friday, August 31 3:00 PM - 5:00 PM",
//
//                    "Saturday, September 1 11:00 AM - 1:00 PM",
//                    "Saturday, September 1 1:00 PM - 3:00 PM",
//                    "Saturday, September 1 3:00 PM - 5:00 PM",



                    "Saturday, October 13 9:00 AM - 11:00 AM",
                    "Saturday, October 13 11:00 AM - 1:00 PM",


                    "Monday, October 15 9:00 AM - 11:00 AM",
                    "Monday, October 15 11:00 AM - 1:00 PM",

                    "Tuesday, October 16 9:00 AM - 11:00 AM",
                    "Tuesday, October 16 11:00 AM - 1:00 PM",
                    "Tuesday, October 16 3:00 PM - 5:00 PM",
                    "Tuesday, October 16 5:00 PM - 7:00 PM",

//                    "Wednesday, October 17 9:00 AM - 11:00 AM",
//                    "Wednesday, October 17 3:00 PM - 5:00 PM",

//                    "Thursday, October 18 9:00 AM - 11:00 AM",
                    "Thursday, October 18 1:00 PM - 3:00 PM",
                    "Thursday, October 18 3:00 PM - 5:00 PM",

                    "Friday, October 19 9:00 AM - 11:00 AM",
                    "Friday, October 19 11:00 AM - 1:00 PM",

                    "Saturday, October 20 9:00 AM - 11:00 AM",
                    "Saturday, October 20 11:00 AM - 1:00 PM",

                    "Monday, October 22 9:00 AM - 11:00 AM",
                    "Monday, October 22 11:00 AM - 1:00 PM",

                    "Tuesday, October 23 9:00 AM - 11:00 AM",
                    "Tuesday, October 23 11:00 AM - 1:00 PM",
                    "Tuesday, October 23 3:00 PM - 5:00 PM",
                    "Tuesday, October 23 5:00 PM - 7:00 PM",

//                    "Wednesday, October 24 9:00 AM - 11:00 AM",
//                    "Wednesday, October 24 3:00 PM - 5:00 PM",

//                    "Thursday, October 25 9:00 AM - 11:00 AM",
                    "Thursday, October 25 1:00 PM - 3:00 PM",
                    "Thursday, October 25 3:00 PM - 5:00 PM",

                    "Friday, October 26 9:00 AM - 11:00 AM",
                    "Friday, October 26 11:00 AM - 1:00 PM",

                    "Saturday, October 27 9:00 AM - 11:00 AM",
                    "Saturday, October 27 11:00 AM - 1:00 PM",


                    "Monday, October 29 9:00 AM - 11:00 AM",
                    "Monday, October 29 11:00 AM - 1:00 PM",

                    "Tuesday, October 30 9:00 AM - 11:00 AM",
                    "Tuesday, October 30 11:00 AM - 1:00 PM",
                    "Tuesday, October 30 3:00 PM - 5:00 PM",
                    "Tuesday, October 30 5:00 PM - 7:00 PM",

//                    "Wednesday, October 31 9:00 AM - 11:00 AM",
//                    "Wednesday, October 31 3:00 PM - 5:00 PM",

//                    "Thursday, November 1 9:00 AM - 11:00 AM",
                    "Thursday, November 1 1:00 PM - 3:00 PM",
                    "Thursday, November 1 3:00 PM - 5:00 PM",

                    "Friday, November 2 9:00 AM - 11:00 AM",
                    "Friday, November 2 11:00 AM - 1:00 PM",

                    "Saturday, November 3 9:00 AM - 11:00 AM",
                    "Saturday, November 3 11:00 AM - 1:00 PM",



                    "Monday, November 5 9:00 AM - 11:00 AM",
                    "Monday, November 5 11:00 AM - 1:00 PM",

                    "Tuesday, November 6 9:00 AM - 11:00 AM",
                    "Tuesday, November 6 11:00 AM - 1:00 PM",
                    "Tuesday, November 6 3:00 PM - 5:00 PM",
                    "Tuesday, November 6 5:00 PM - 7:00 PM",

//                    "Wednesday, October 24 9:00 AM - 11:00 AM",
//                    "Wednesday, October 24 3:00 PM - 5:00 PM",

//                    "Thursday, November 8 9:00 AM - 11:00 AM",
                    "Thursday, November 8 1:00 PM - 3:00 PM",
                    "Thursday, November 8 3:00 PM - 5:00 PM",

                    "Friday, November 9 9:00 AM - 11:00 AM",
                    "Friday, November 9 11:00 AM - 1:00 PM",

                    "Saturday, November 10 9:00 AM - 11:00 AM",
                    "Saturday, November 10 11:00 AM - 1:00 PM",


                    "Monday, November 12 9:00 AM - 11:00 AM",
                    "Monday, November 12 11:00 AM - 1:00 PM",

                    "Tuesday, November 13 9:00 AM - 11:00 AM",
                    "Tuesday, November 13 11:00 AM - 1:00 PM",
                    "Tuesday, November 13 3:00 PM - 5:00 PM",
                    "Tuesday, November 13 5:00 PM - 7:00 PM",

//                    "Wednesday, October 24 9:00 AM - 11:00 AM",
//                    "Wednesday, October 24 3:00 PM - 5:00 PM",

//                    "Thursday, November 15 9:00 AM - 11:00 AM",
                    "Thursday, November 15 1:00 PM - 3:00 PM",
                    "Thursday, November 15 3:00 PM - 5:00 PM",

                    "Friday, November 16 9:00 AM - 11:00 AM",
                    "Friday, November 16 11:00 AM - 1:00 PM",

                    "Saturday, November 17 9:00 AM - 11:00 AM",
                    "Saturday, November 17 11:00 AM - 1:00 PM",

                ];
                $query = "SELECT study_date FROM demographics WHERE study_date !='' AND study_date IS NOT NULL";
//                dd(\Illuminate\Support\Facades\DB::select($query));
                $results = \Illuminate\Support\Facades\DB::select($query);


                $taken_dates = array();
                foreach($results as $key=>$val){
                    $taken_dates[] = $val->study_date;
                }



                $available_dates = array_diff($all_dates,$taken_dates);
                $available_dates_select = array();

                foreach($available_dates as $val){
                    $available_dates_select[$val]=$val;
                }

             ?>
            <div class="form-group">
                <div>Study Date (Select One):</div>
                {!! Form::select('study_date',$available_dates_select); !!}

            </div>
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




{{--            <p>{!! NoCaptcha::display() !!}</p>--}}

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
