<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

            });
        </script>
    </head>

    <body>

          <body>

        <div class="container">
            
            <h2>Break</h2>
            
            <title>Example of Bootstrap 3 Readonly Inputs</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
    .bs-example{
    	margin: 20px;
    }
</style>
<body>
    
    <div class="bs-example">
        <div class="container">
            <div class="well">

                <p><strong>Please take a 2-min break and ask any question(s) you have.</strong></p>

                <p>Please note that at present, you are asked to carry out two tasks using online searching. However, we are interested in your thinking of problems that you face while looking for information in general. We will ask you at various times what would be your ideal method(s) (which means you can imagine and choose support(s) or help(s) which may not be available in the current system) to achieve your goal and help/support you expect.</p>

            </div>
        </div>
    </div>
</body>
            
            
            <form method="POST" action="/break">
                {{ csrf_field() }}



            <br><br>

            <button type = "submit" class = "btn btn-success">Next</button>
                
            <br><br>

            </form>

        </div>

    </body>

</html>