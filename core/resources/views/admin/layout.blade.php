<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Coagmento 2.0</title>
        <link rel="icon" type="image/png" href="images/coagfavicon.png" />
        <!-- Fonts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}



        <style>
            body {
                font-family: 'Lato';
            }
            .fa-btn {
                margin-right: 6px;
            }
        </style>
        {{--<script src="jquery-3.3.1.min.js"></script>--}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <link href="/css/splash.css" rel="stylesheet" />

        <meta name="csrf_token" content="{{csrf_token()}}">
        <style>
            .card {
                box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
                transition: 0.3s;
                border-radius: 5px;
                background-color: #fcffff;
            }
            .card:hover {
                box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            }

            /* Add some padding inside the card container */
            .contain {
                padding: 16px 16px;
                margin-bottom: 1em;
            }
            /*Toggle extension switch styling refer w3schools.com/howto/howto_css_switch.asp*/
            .switch{
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }
            .switch input{display:none;}
            .slider{
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
            }
            .slider:before{
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: #84af84;

            }
            input:checked + .slider{
                background-color: #c3ffb8;
            }
            input:focus + .slider{
                box-shadow: 0 0 1px #05f305;
            }
            input:checked + .slider:before{
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }
            .slider.round{
                border-radius: 34px;
            }
            .slider.round:before{
                border-radius: 50%;
            }


        </style>



        @yield('header')

    </head>

    <body id="app-layout">
    <div class="main" style="background-image: url('/../images/splash/bg16.jpg'); ">
    <nav class="navbar navbar-default navbar-static-top" >
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                {{--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>--}}

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/admin/') }}">Home</a>
                <a class="navbar-text" href="{{ url('/admin/manage_users') }}">Manage Users</a>
                <a class="navbar-text" href="{{ url('/admin/manage_tasks') }}">Manage Tasks</a>
                <a class="navbar-text" href="{{ url('/admin/manage_questionnaires') }}">Manage Questionnaires</a>
                <a class="navbar-text" href="{{ url('/admin/manage_stages') }}">Manage Stages</a>
                <!-- <a class="navbar-text" href="{{ url('/admin/study_design') }}">Manage Study</a> -->
                <a class="navbar-text" href="{{ url('/admin/manage_emails') }}">Manage Emails</a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <!--<li><a href="{{ url('#') }}">Placeholder #link</a></li>-->
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/auth/login') }}">Login</a></li>
                        <li><a href="{{ url('/auth/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                @if(Auth::user()->is_admin)
                                    <li><a href="{{ url('/admin/manage_users') }}"><i class="fa fa-btn fa-user"></i>Manage Users</a></li>
                                    <li><a href="{{ url('/admin/manage_tasks') }}"><i class="fa fa-btn fa-tasks"></i>Manage Tasks</a></li>
                                    <li><a href="{{ url('/admin/manage_emails') }}"><i class="fa fa-btn fa-envelope"></i>Manage Emails</a></li>
                                    <li><a href="{{ url('/admin/manage_questionnaires') }}"><i class="fa fa-btn fa-pencil-square-o"></i>Manage Questionnaires</a></li>
                                    <li><a href="{{ url('/admin/manage_stages') }}"><i class="fa fa-btn fa-th-list"></i>Manage Stages</a></li>
                                    <!-- <li><a href="{{ url('/admin/study_design') }}"><i class="fa fa-btn fa-cogs"></i>Study Design</a></li> -->
                                @endif
                                <li><a href="{{ url('/auth/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    @yield('content')

        <div class="footer">
        </div>

    <!-- JavaScripts -->


    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    </div>
    </body>
</html>