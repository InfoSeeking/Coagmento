@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
   {{-- <style>
        #fb-rendered-form {
            clear: both;
            display: none;
        }
        #edit-form{
            float:right;
        }
    </style>--}}
@stop

@section('content')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>

    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ $questionnaire->title }}
            </div>
            <div class="panel-body">
                <div id="fb-render"></div>
                <a class="btn btn-default" href="/admin/manage_questionnaires">Return</a>
            </div>
        </div>
    </div>

    <script>
        jQuery(function($) {
        var fbRender = document.getElementById('fb-render'),
            formData = JSON.stringify({!! $questions !!});

        var formRenderOpts = {
            dataType: 'json',
            formData
        };

        $(fbRender).formRender(formRenderOpts);
        });
    </script>
@stop