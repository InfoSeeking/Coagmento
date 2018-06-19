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
                        <h4>{{ $attribute->name }}</h4>
                        @if($attribute->type === "text")
                            <input type="text" id="value" name="value" style="float: top;">
                        @elseif($attribute->type === "select")
                                <select name="value" id="value">

                                    @foreach($attribute->option_name as $key=>$value)
                                        <option value="{{$value}}"{{--id="{{$key}}" name="{{$key}}"--}}>
                                            {{$value}}
                                        </option>
                                    @endforeach

                                </select><br>
                            <!--<i class="fa fa-btn fa-plus-circle">Add Option</i>-->
                        @endif
                        <br>
                        <span>
                            <a href="/admin/{{ $attribute->id }}/edit_attribute" class="btn btn-warning btn-sm" style="float:left">Edit</a>
                            <form action="/admin/{{ $attribute->id }}/delete_attribute" id="delete">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <span{{--class="form-group"--}}>
                                    <a type="submit" onclick="return confirmChoice()" class="btn btn-danger btn btn-sm" style="float: left" href="/admin/{{ $attribute->id }}/delete_attribute">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </a>
                                </span>
                            </form>
                            <script>
                                function confirmChoice(){
                                    return confirm("Are you sure you want to delete this Attribute?");
                                }
                            </script>
                        </span>
                        <br>
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
                    <span class="button-add" style="float: right;"></span>
                </div>
                <span class="form-g"></span>

                   <!-- <label for="value">Attribute value: </label>
                    <input type="text" name="value" id="value"><br>
                    <span class="form-g"></span>-->
                <div class="input-field col s12">
                    <button class="btn btn-primary" type="submit">+ Add Attribute</button>
                </div>
            </form>

            <script>
                $(document).ready(function(){

                    $(document).on('click', '.destroy', function(e){
                        e.preventDefault();
                        $(this).parent(".input-field").remove();
                    });
                    //var addButton=
                    var material = '<div class="input-field col input-g s12">' +
                       /* '<label for="option_name">Options: </label>'+*/
                        '<button type="button" style="float:left;" name="add-option" class="add-option btn btn-sm btn-success">' +
                        '<span class="glyphicon glyphicon-plus"></span>' +
                        '</button>' +
                        '<span style="float:left; cursor:pointer;" class="destroy btn btn-sm btn-danger">Delete</span>' +
                        '<input name="option_name[]" id="option_name[]" type="text" class="form-control">' +

                        '<!--<span class="add-option btn-sm btn-primary" style="cursor:pointer;">Add Another</span>-->' +
                        '</div>';


                    $(document).on('click', '.add-option', function(){
                        $(".form-g").append(material);
                    });

                    $(document).on('change', '#type', function (){
                        var selected_option = $('#type :selected').val();
                        if(selected_option === "select" || selected_option === "checkbox"){
                            //$(".button-add").html(addButton);
                            $(".form-g").html(material);
                        }
                        else{
                            //$(".button-add").remove();
                            $(".input-g").remove();
                        }
                    });
                });
            </script>
        </div>
        </div>
    </div>
@stop