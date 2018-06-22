@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
@stop

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel panel-heading">Create a New Questionnaire</div>
            <div class="panel panel-body">
                <form method="POST" action="/admin/manage_tasks">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}

                    <div class="form-group">
                        <div>
                            Title: <br>
                            <textarea rows="1" name="title" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            Description: <br>
                            <textarea rows="4" name="description" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="modal fade" id="newQuestion" tabindex="-1" role="dialog" aria-labelledby="newQuestionLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="newQuestionTitle">Question</h4>
                                </div>
                                <form action="/admin/{question}/add" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <div class="modal-body input-field col s12">
                                        <div class="form-group">
                                            <textarea rows="1" name="question_title" class="form-control" ></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="question_type">Question Type:</label>
                                            <select class="form-control" id="question_type">
                                                <option>input</option>
                                                <option>textarea</option>
                                                <option>checkbox</option>
                                                <option>checkbox inline</option>
                                                <option>radio</option>
                                                <option>radio inline</option>
                                                <option>select</option>
                                            </select>
                                        </div>
                                        <span class="form-g"></span>
                                    </div>
                                    <span>
                                        <button type="submit" class="btn btn-success add-option">Add Question</button>
                                        <a href="/admin/create_questionnaire" class="btn btn-default">Cancel</a>
                                    </span>
                                </form>
                            </div>
                        </div>
                    </div>


                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#newQuestion">Add Question</button>

                    <span class="center-block">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="/admin/manage_tasks" class="btn btn-default">Cancel</a>
                    </span>
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

                        $(document).on('change', '#question_type', function (){
                            var selected_option = $('#question_type :selected').val();
                            if(selected_option === "input"){
                                //$(".button-add").html(addButton);
                                $(".form-g").html(material);
                            }
                            else if(selected_option === "textarea"){
                                var mat =
                                    '<div class="input-field col input-g s12">' +
                                    '<span style="float:left; cursor:pointer;" class="destroy btn btn-sm btn-danger">Delete</span>' +
                                    '<textarea rows="4" name="" class="form-control"></textarea>'+
                                    '</div>';
                                $(".form-g").html(mat);
                            }
                            else if(selected_option === "checkbox"){
                                var mat =
                                    '<div class="input-field col input-g s12">' +
                                    '<span style="float:left; cursor:pointer;" class="destroy btn btn-sm btn-danger">Delete</span>' +
                                    '<div class="checkbox"><label><input type="checkbox" value=""></label></div>'+
                                    '</div>';
                            }
                            else if(selected_option === "checkbox-inline"){

                            }
                            else if(selected_option === "radio"){

                            }
                            else if(selected_option === "radio-inline"){

                            }
                            else if(selected_option === "select"){

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