@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
    <script src="jquery-3.3.1.min.js"></script>
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Attribute</div>
            <div class="panel-body">
                <form method="POST" action="/admin/{{ $attribute->id }}/update_attribute">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="input-field col s12">
                        <label for="type">Attribute type:</label>
                        <select name="type" id="type">
                            @if( "text" === $attribute->type)
                                <option value="text" selected disabled>Text</option>
                            @else
                                <option value="select" selected disabled>Select</option>
                            @endif
                        </select><br>
                    </div>

                    <div class="input-field col s12">
                        <label for="name">Attribute name: </label>
                        <input type="text" name="name" id="name" value="{{ $attribute->name }}"><br>
                        @if($attribute->type === 'select')
                            <span class="btn btn-success btn-sm add-option" style="float: left;"> <i class="glyphicon glyphicon-plus"></i></span>
                        @endif
                        <br>
                    </div>
                    <span class="form-g">
                        <br>
                        @if($attribute->type === 'select')
                        @foreach($attribute->option_name as $key=>$value)
                            <div class="option input-field">
                                <span style="float:left; cursor:pointer;" class="destroy btn btn-sm btn-danger">Delete</span>
                                <input name="option_name[]" id="option_name[]" value="{{$value}}" type="text" class="form-control">
                            </div>
                        @endforeach
                        @endif
                    </span>

                    <!-- <label for="value">Attribute value: </label>
                     <input type="text" name="value" id="value"><br>
                     <span class="form-g"></span>-->
                    <div class="input-field col s12"><hr>
                        <button class="btn btn-warning" type="submit">Update</button>
                        <a href="/admin/task_settings" class=" btn btn-default">Return</a>
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
                            '<span style="float:left; cursor:pointer;" class="destroy btn btn-sm btn-danger">Delete</span>' +
                            '<input name="option_name[]" id="option_name[]" type="text" class="form-control">' +
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
