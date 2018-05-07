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
            
            <h2>Questionnaire</h2>
            
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

                });
            </script>
<body>
    
    <div class="bs-example">
        <div class="container">Please read the task below and answer the following questions:<br><br>
            <div class="well">
                <br><br>
                {{ $task['description'] }}
            </div>
        </div>
    </div>
</body>


            @if(count($errors))
                <div class="alert alert-danger">
                    <ul>

                        @foreach($errors->all() as $error)

                            <li>{{$error}}</li>

                        @endforeach

                    </ul>

                </div>
            @endif

            {!! Form::open(['url' => '/questionnaire_pretask']) !!}

            {{ csrf_field() }}



            {!! Form::label('task_knowledge','How knowledgeable do you think you are on this topic?') !!}
            <div class="radio">
                <label>{!! Form::radio('task_knowledge',1) !!}1 (No knowledge at all)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowledge',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowledge',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowledge',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowledge',5) !!}5 (Highly knowledgeable)</label>
            </div>
            <br><br>




            {!! Form::label('task_interest','Is this task interesting to you?') !!}
            <div class="radio">
                <label>{!! Form::radio('task_interest',1) !!}1 (Not interesting at all)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_interest',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_interest',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_interest',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_interest',5) !!}5 (Very interesting)</label>
            </div>
            <br><br>






            {!! Form::label('search_difficulty','How difficult do you think it will be to search for information for this task using a search engine?') !!}
            <div class="radio">
                <label>{!! Form::radio('search_difficulty',1) !!}Not at all difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('search_difficulty',2) !!}Slightly difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('search_difficulty',3) !!}Somewhat difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('search_difficulty',4) !!}Moderately difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('search_difficulty',5) !!}Very difficult</label>
            </div>
            <br><br>




            {!! Form::label('information_understanding','How difficult do you think it will be to understand the information in the search engine fields?') !!}
            <div class="radio">
                <label>{!! Form::radio('information_understanding',1) !!}Not at all difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_understanding',2) !!}Slightly difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_understanding',3) !!}Somewhat difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_understanding',4) !!}Moderately difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_understanding',5) !!}Very difficult</label>
            </div>
            <br><br>




            {!! Form::label('decide_usefulness','How difficult do you think it will be to decide if the information the search engine finds is useful for completing the task?') !!}
            <div class="radio">
                <label>{!! Form::radio('decide_usefulness',1) !!}Not at all difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('decide_usefulness',2) !!}Slightly difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('decide_usefulness',3) !!}Somewhat difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('decide_usefulness',4) !!}Moderately difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('decide_usefulness',5) !!}Very difficult</label>
            </div>
            <br><br>




            {!! Form::label('information_integration','How difficult do you think it will be to integrate the information in the search engine fields?') !!}
            <div class="radio">
                <label>{!! Form::radio('information_integration',1) !!}Not at all difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_integration',2) !!}Slightly difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_integration',3) !!}Somewhat difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_integration',4) !!}Moderately difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_integration',5) !!}Very difficult</label>
            </div>
            <br><br>




            {!! Form::label('information_sufficient','How difficult do you think it will be to determine when you have enough information to finish the task?') !!}
            <div class="radio">
                <label>{!! Form::radio('information_sufficient',1) !!}Not at all difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_sufficient',2) !!}Slightly difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_sufficient',3) !!}Somewhat difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_sufficient',4) !!}Moderately difficult</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('information_sufficient',5) !!}Very difficult</label>
            </div>
            <br><br>


            <button type = "submit" class = "btn btn-success">Submit</button>

            <br><br>

            {!! Form::close() !!}
            
            





            </form>
            


        </div>

    </body>

</html>
