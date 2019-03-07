@extends('participant.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            border-radius: 5px; /* 5px rounded corners */
            background: #f8f8f8;
        }
        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        /* Add some padding inside the card container */
        .contain {
            padding: 16px 16px;
            margin-bottom: 1em;
        }
    </style>
@stop

@section('content')
    <div class="container">
        @if(!$stage->widgets()->exists())
            <div class="card sortable-element" name="card[]" id="card[]">
                <div class="card-body">
                    <div class="container contain">
                        <p> There are no widgets here. </p>
                    </div>
                </div>
            </div>
        @else
            @foreach($widgets as $widget)
                <div class="card sortable-element" name="card[]" id="card[]">
                    <div class="card-body">
                        <div class="contain">
                            @if($widget->type === "text")
                                <p>{{$widget->value}}</p>
                            @elseif($widget->type === "template")
                                <input type="hidden" value="{{ $task = $tasks->where('description', $widget->value )->first() }}">
                                <p><b> {{ $task->description }} </b></p>
                                @foreach($attributes as $attribute)
                                    <p>
                                        <b>{{ $attribute->name }}:</b>
                                        {{ $assignments->where('task_id', $task->id)
                                        ->where('attribute_id', $attribute->id)->first()['value'] }}
                                    </p>
                                @endforeach
                            @elseif($widget->type === "questionnaire")
                                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
                                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
                                <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
                                <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        {{ $questionnaires->where('title', $widget->value)->first()->title }}
                                    </div>
                                    <div class="panel-body">
                                        <div id="fb-render"></div>
                                    </div>
                                </div>


                                <script>
                                    jQuery(function($) {
                                        var fbRender = document.getElementById('fb-render'),
                                            formData = JSON.stringify({!! $questionnaires->where('title', $widget->value)->first()->data !!});

                                        var formRenderOpts = {
                                            dataType: 'json',
                                            formData
                                        };

                                        $(fbRender).formRender(formRenderOpts);
                                    });
                                </script>
                            @elseif($widget->type === "resource")
                                {!! $widget->value !!}
                            @else
                                <div class="checkbox">
                                    <label><input type="checkbox" name="value[]">{{ $widget->value }}</label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!--i@f($prevStage!==null)
            <a class="btn btn-success btn-large" style="float: left; background-color: rgba(13,240,56,0.15)" href="/study_start/{{$prevStage->id}}">Previous</a>
        e@ndif-->

        @if($nextStage===null)
            <a class="btn btn-success btn-large" style="float: right; background-color: rgba(13,240,56,0.15)" href="/auth/logout">Log Out</a>
        @else
            <a class="btn btn-success btn-large" style="float: right; background-color: rgba(13,240,56,0.15)" href="/study_start/{{$nextStage->id}}">Continue</a>
        @endif
        <br>
        <hr>
        <a class="btn btn-default" style="background-color: rgba(255,255,255,0.51); color: #1a1a1a" href="/admin/manage_stages">Return</a>
    </div>
@stop
