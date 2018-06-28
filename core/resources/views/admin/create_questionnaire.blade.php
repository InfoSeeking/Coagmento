@extends('admin.layout')

@section('header')
    <meta name="csrf_token" content="{{csrf_token()}}">
    <style>
        #fb-rendered-form {
            clear: both;
            display: none;
        }
        #edit-form{
            float:right;
        }
    </style>
@stop

@section('content')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>

    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Create/Edit Questionnaire
            </div>
            <div class="panel-body">
            <form method="POST" action="/admin/create_questionnaire">

                {{ csrf_field() }}
                {{ method_field('POST') }}
                <label for="title">Title</label>
                <input class="form-control form-group" type="text" id="title" name="title">

                <div id="fb-editor"></div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success" id="getJSON">Save</button>
                </div>
            </form>
            <div id="fb-rendered-form">
                <form action="#"></form>
                <button class="btn btn-default edit-form" id="edit-form">Edit</button>
            </div>

            <script>
                jQuery(function($) {
                    var $fbEditor = $(document.getElementById('fb-editor')),
                        $formContainer = $(document.getElementById('fb-rendered-form')),
                        fbOptions = {
                            onSave: function() {
                                $fbEditor.toggle();
                                $formContainer.toggle();
                                $('form', $formContainer).formRender({
                                    formData: formBuilder.formData
                                });
                            }
                        },
                        formBuilder = $fbEditor.formBuilder();

                    document.getElementById('getJSON').addEventListener('click', function() {
                        event.preventDefault(); //check notes
                        var data = formBuilder.actions.getData('json');
                        var title = document.getElementById('title').value;
                        console.log(data);
                        $.ajax({
                            url: "/admin/create_questionnaire",
                            type: "POST",
                            data: {title: title, questions: JSON.parse(data)},
                            dataType: "json",
                            success: function(result) {
                                console.log('it worked');
                                console.log(result);
                                alert(result);
                            },
                            error: function(textStatus, errorThrown) {
                                console.log('it didnt work');
                                console.log(textStatus);
                                console.log(errorThrown);
                            },
                        });
                    });

                    $('.edit-form', $formContainer).click(function() {
                        $fbEditor.toggle();
                        $formContainer.toggle();
                    });
                    //formBuilder.actions.getData('json');
                });

            </script>
            </div>
        </div>
    </div>

@stop
