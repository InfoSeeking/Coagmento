@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
    <script src="jquery-3.3.1.min.js"></script>
@stop

@section('content')



    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Attributes</div>
            <div class="panel-body">
                @foreach($attributes as $attribute)
                    <form method="POST" action="/admin/{{ $attribute->id }}/update_attribute">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <h4>{{ $attribute->name }}</h4>
                        @if($attribute->type === "text")
                            <input type="text" id="value" name="value">
                        @elseif($attribute->type === "select")
                                <select name="value" id="value">

                                    @foreach($attribute->option_name as $key=>$value)
                                        <option value="{{$value}}" id="{{$key}}" name="{{$key}}">
                                            {{$value}}
                                        </option>
                                    @endforeach

                                </select><br>
                            <!--<i class="fa fa-btn fa-plus-circle">Add Option</i>-->
                        @endif
                        <br>
                        <div>
                            <button type="submit" class="btn btn-warning btn-sm">Update</button>
                        </div>
                    </form>
                    <hr>
                @endforeach
                <button class="btn btn-primary">Save Settings</button>
                <a href="manage_tasks" class="btn btn-default">Return to Tasks</a>
            </div>

        </div>

        <div class="panel panel-default">
        <div class="panel-heading">Add an Attribute</div>
        <div class="panel-body">
            <form method="POST" action="/admin/task_settings" id="store">
                {{ csrf_field() }}
                {{ method_field('POST') }}
                <div class="input-field col s12">
                    <label for="type">Attribute type:</label>
                    <select name="type" id="type">
                        <option value="" disabled selected>Choose your attribute type</option>
                        <option value="text">Text</option>
                        <option value="select">Select</option>
                    </select><br>
                </div>

                <div class="input-field col s12">
                    <label for="name">Attribute name: </label>
                    <input type="text" name="name" id="name"><br>
                </div>
                <span class="form-g"></span>

                   <!-- <label for="value">Attribute value: </label>
                    <input type="text" name="value" id="value"><br>
                    <span class="form-g"></span>-->
                <div class="input-field col s12">
                    <button class="btn btn-default" type="submit">+ Add Attribute</button>
                </div>
            </form>

            <script>
                $(document).ready(function(){

                    $(document).on('click', 'delete-option', function(){
                        $(this).parent(".input-field").remove();
                    })
                    var material = '<div class="input-field col input-g s12">' +
                        '<label for="option_name">Options: </label>'+
                        '<input name="option_name[]" id="option_name[]" type="text">' +
                        '<span style="cursor:pointer;" class="destroy btn-sm btn-danger">Delete</span>' +
                        '<span class="add-option btn-sm btn-primary" style="cursor:pointer;">Add Another</span>' +
                        '</div>';

                    $(document).on('click', '.add-option', function(){
                        $(".form-g").append(material);
                    });

                    $(document).on('change', '#type', function (){
                        var selected_option = $('#type :selected').val();
                        if(selected_option === "select" || selected_option === "checkbox"){
                            $(".form-g").html(material);
                        }
                        else{
                            $(".input-g").remove();
                        }
                    });
                });
            </script>
        </div>
        </div>
    </div>
@stop