@extends('admin.layout')

@section('header')

@stop

@section('content')
    <form method="POST" action="/admin/create_stage">
        {{ csrf_field() }}
        {{ method_field('POST') }}
        <div class="container">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            {{--@if($error === 'The *.value field is required.')
                                <li>All values must be filled in.</li>
                            @else--}}
                                <li>{{ $error }}</li>
                            {{--@endif--}}
                        @endforeach
                    </ul>
                </div>
            @endif
            <label for="title">Stage Title</label>
            <input class="form-control form-group" type="text" id="title" name="title">
            <label for="toggleextension">Toggle Extension</label><br>
            <label class="switch" id="toggleextension">
                <input type="checkbox" id="toggle" name="toggle_extension" checked>
                <span  class="slider round"></span>
            </label>

        </div>
        <div class="container form-g" id="sortable"></div>
        <br>
        <div class="container">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-widget">Add a Widget</button>
        </div>
        <div class="container">
            <span class="widget"></span>
            <div class="form-group">
                <hr>
                <button type="submit" class="btn btn-success">Save</button>
                <a href="manage_stages" class="btn btn-default">Return</a>
            </div>

        </div>
    </form>
    <div class="container">
        <div class="modal fade" id="add-widget" tabindex="-1" role="dialog" aria-labelledby="add-widget-label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="/admin/create_widget">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">Close</span> </button>
                            <h4 class="modal-title" id="add-widget-label">Add Widget</h4>
                        </div>
                        <div class="modal-body">

                            <h4>Please select widget type: </h4>
                            <select name="type" id="type">
                                <option value="text">Text</option> (static; text should be marked up), templated text/templated item (e.g. task prompt), questionnaire, a resource (e.g. video), or confirm button"
                                <option value="template">Template of item/text</option>
                                <option value="questionnaire">Questionnaire</option>
                                <option value="resource">Video/Image/File</option>
                                <option value="confirm">Confirm Button</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="getData" name="add-widget">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(function($) {
            var divs='<div class="card sortable-element" name="card[]" id="card[]"><div class="card-body"><div class="contain"><br>' +
                '<span style="float:left; cursor:pointer;" class="destroy btn btn-sm btn-danger">Delete</span><br><br>';
            var material = divs;
            document.getElementById('getData').addEventListener('click', function() {
                event.preventDefault(); //check notes
                var type = document.getElementById('type').value;
                if(type==="text"){
                    material+= '<input type="hidden" id="widget[]" name="widget[]" value="text">';
                    material+='<textarea class="form-group form-control" rows="5" name="value[]" id="value"></textarea>';
                } else if(type==="template"){
                    material+= '<input type="hidden" id="widget[]" name="widget[]" value="template">';
                    material+='<div class="form-group">'+
                        '<label for="Tasks">Select Task:</label>'+
                        '<select class="form-control" id="Tasks" name="value[]">'+
                        '@foreach($tasks as $task)'+
                        '<option id="{{$task->id}}">{{$task->description}}</option>'+
                        '@endforeach' +
                        '</select>' +
                        '</div>';
                } else if(type==="questionnaire"){
                    material+= '<input type="hidden" id="widget[]" name="widget[]" value="questionnaire">';
                    material+='<div class="form-group">'+
                        '<label for="questionnaires">Select Questionnaire:</label>'+
                        '<select class="form-control" id="questionnaires" name="value[]">'+
                        '@foreach($questionnaires as $questionnaire)'+
                            '<option id="{{$questionnaire->id}}">{{$questionnaire->title}}</option>'+
                        '@endforeach' +
                        '</select>' +
                        '</div>';
                } else if(type==="resource"){
                    material+= '<input type="hidden" id="widget[]" name="widget[]" value="resource">';
                    material+='<label for="resource">Resource Link</label>'+
                        '<textarea class="form-group form-control" id="resource" rows="1" name="value[]" id="value"></textarea>';
                } else {
                    material+= '<input type="hidden" id="widget[]" name="widget[]" value="confirm">';
                    material+='<div class="form-group">'+
                    '<input type="checkbox" name="checkbox">' +
                        '<label for="confirmDescription"> Enter a confirmation message: </label>' +
                        '<textarea class="form-group form-control" rows="2" name="value[]" id="confirmDescription"></textarea></div>';
                }
                material+='</div></div></div>';
                $(".form-g").append(material);
                material =divs;

            });
            $(document).on('click', '.destroy', function(e){
                e.preventDefault();
                $(this).closest(".card").remove();
            });
        });
    </script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        //Handle Drag and Drop
        $(document).ready(function () {
            $('#sortable').sortable({
                axis:'y',
                update:function(event, ui){
                    var data= $(this).sortable('toArray');
                    console.log(data);
                }
            });
            $("#sortable").disableSelection();
        });
    </script>
@stop