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
            
            <h2>Welcome to the Problem-Help Study!</h2>
            
            <title>Welcome</title>
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
            <div class="well">Please read the general instructions below before proceeding to the next stage:</div>
        </div>
    </div>
</body>
            
            
            <form method="POST" action="/welcome">
                {{ csrf_field() }}





                <ul>
                    <li>In this study, you must complete <strong>three</strong> tasks.</li>
                    <li>The first one is a <strong>5-minute practice task</strong> to make you familiar with the flow of this study.</li>
                    <li>After finishing the practice or warm-up task, you need to complete <strong>two regular tasks</strong>, each within a time-frame of <strong>20 minutes</strong>. For each task, you are required to <strong>search on the web</strong> for useful information, <strong>bookmark relevant webpages</strong>, and finally <strong>write up your findings</strong>. Specific instructions for completing each task will be provided with the task description.</li>
                    <li>Before, during, and after each task, you need to <strong>fill out short questionnaires</strong> regarding the task.</li>
                    <li>If you <strong>click</strong> on the blue <strong>"Coagmento" button</strong> on the <strong>top-right corner</strong> of your browser, a <strong>pop-up window</strong> will appear that <strong>shows your workspace</strong> (the bookmarks you have collected, the Etherpad editor).</li>
                    <li>During the task, whenever you want to bookmark a webpage, just <strong>right click</strong> on the page and you will find a <strong>bookmark option</strong> to click with a blue Coagmento label with it. The bookmarked page will appear on the Coagmento workspace under “Bookmark”.</li>
                    <li>During the task, when you <strong>go to Google</strong> to search for information, you will get desktop-notifications to open Coagmento workspace and you <strong>need to complete</strong> the questionnaire.</li>
                    <li>On the Coagmento workspace, you will find a <strong>link to "Etherpad"</strong> where you can write your answers or findings.</li>
                    <li>At the <strong>end</strong> of the task-session, there will be an <strong>interview</strong> session in which we will ask questions about your experience with this study.</li>
                </ul>


                <button type = "submit" class = "btn btn-success">Next</button>
                
            <br><br>

            </form>

        </div>

    </body>

</html>