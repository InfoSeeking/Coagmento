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

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach
            </ul>
        </div>
    @endif

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>

    <div class="container">
        <div class="panel panel-default">

            <div class="panel panel-heading">Edit Questionnaire</div>

            <div class="panel-body">
                <form method="POST" action="/admin/{{$questionnaire->id}}/edit_questionnaire">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <label for="title">Title</label>
                    <input class="form-control form-group" type="text" id="title" name="title" value="{{ old('title', $questionnaire->title) }}">
                    <div id="fb-editor"></div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success" id="getJSON">Save</button>
                        <a class="btn btn-default" href="/admin/manage_questionnaires">Return</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        jQuery(function($) {
            var $fbEditor = $(document.getElementById('fb-editor')),
                questionsJSON = JSON.stringify({!! $questions !!}),
                options = {
                    dataType: 'json',
                    formData: questionsJSON
                },
                formBuilder = $fbEditor.formBuilder(options);

            document.getElementById('getJSON').addEventListener('click', function() {
                event.preventDefault();
                var data = formBuilder.actions.getData('json');
                var title = document.getElementById('title').value;
                console.log(data);

                $.ajax({
                    url: "/admin/" + {{$questionnaire->id}} + "/edit_questionnaire",
                    type: "PATCH",
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
        });

    </script>
@stop