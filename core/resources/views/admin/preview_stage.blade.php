@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            border-radius: 5px; /* 5px rounded corners */
        }
        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        /* Add some padding inside the card container */
        .contain {
            padding: 2px 16px;
        }
    </style>
@stop

@section('content')
    <div class="container">
        @foreach($widgets as $widget)
            <div class="card sortable-element" name="card[]" id="card[]">
                <div class="card-body">
                    <div class="container contain"><br>
                        @if($widget->type === "text")
                            <p>{{$widget->value}}</p>
                        @elseif($widget->type === "template")
                            <p>{{--Task Description--}}</p>
                            <p> {{--foreach loop through attributes, check if null, if null, do not display--}}</p>
                        @elseif($widget->type === "questionnaire")
                            {{ $questionnaire = $questionnaires->where('title',$widget->value) }}
                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
                            <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
                            <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ dd($questionnaire->title) }}
                                </div>
                                <div class="panel-body">
                                    <div id="fb-render"></div>
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
                        @elseif($widget->type === "resource")

                        @else
                            <div class="checkbox">
                                <label><input type="checkbox" name="value[]">Confirm</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <br>
        @endforeach
            {{--The link should eventually look something like href="userstudy/{stage}/stage" --}}
            <a class="btn btn-success" href="#">Continue</a>
            <a class="btn btn-default" href="/admin/manage_stages">Return</a>
    </div>
@stop
