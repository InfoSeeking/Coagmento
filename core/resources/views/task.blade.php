<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>

    <body>

          <body>

        <div class="container">
            
            <h2>{{$header}}</h2>
            
            <title>Example of Bootstrap 3 Readonly Inputs</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
    .bs-example{
    	margin: 20px;
    }
</style>

            <script>
                $( document ).ready(function() {

                    $('form').submit(function(){
                        var local_ms = new Date();
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'created_at_local_ms',
                            name: 'created_at_local_ms',
                            value: local_ms.getTime()
                        }).appendTo('form');

                        $('<input>').attr({
                            type: 'hidden',
                            id: 'created_at_local',
                            name: 'created_at_local',
                            value: local_ms/1000
                        }).appendTo('form');
                    });

                    $('#task_confirm_button').click(
                        function(){
                                return confirm("Are you sure you want to continue? If so, you will stop working on the task.");
                        }
                    );

                });
            </script>
<body>
    
    <div class="bs-example">
        <div class="container">
            {{--Here's the description.--}}
            <p>{{ $instructions }}</p>
            <div class="well">
                {{--Please read the instructions below:--}}
                {{--<br><br>--}}
                {{ $task['description'] }}

            </div>
            <p>{!! $supplemental_instructions !!}</p>
        </div>
    </div>
</body>
            


            <form method="POST" action="/task">
                {{ csrf_field() }}




            <button id = "task_confirm_button" type = "submit" class = "btn btn-success">Next</button>
                

            </form>

        </div>

    </body>

</html>