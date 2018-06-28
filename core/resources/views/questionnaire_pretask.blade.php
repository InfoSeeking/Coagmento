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
                <label>{!! Form::radio('task_interest',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_interest',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_interest',7) !!}7 (Very interesting)</label>
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


            <h4>
                Based on your search task, please indicate your level of agreement with the following 10 statements on a 7-point scale from strongly disagree (1) to strongly agree (7).
            </h4>



            {!! Form::label('topic_prev_knowledge','I already know a lot about this topic.') !!}
            <div class="radio">
                <label>{!! Form::radio('topic_prev_knowledge',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('topic_prev_knowledge',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('topic_prev_knowledge',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('topic_prev_knowledge',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('topic_prev_knowledge',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('topic_prev_knowledge',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('topic_prev_knowledge',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('goal_specific','The task goal(s) are very specific.') !!}
            <div class="radio">
                <label>{!! Form::radio('goal_specific',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('goal_specific',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('goal_specific',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('goal_specific',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('goal_specific',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('goal_specific',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('goal_specific',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('task_pre_difficulty','I think the task will be difficult.') !!}
            <div class="radio">
                <label>{!! Form::radio('task_pre_difficulty',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_pre_difficulty',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_pre_difficulty',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_pre_difficulty',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_pre_difficulty',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_pre_difficulty',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_pre_difficulty',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('narrow_information','The information requested is narrowly focused.') !!}
            <div class="radio">
                <label>{!! Form::radio('narrow_information',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('narrow_information',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('narrow_information',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('narrow_information',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('narrow_information',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('narrow_information',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('narrow_information',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('task_newinformation','The task description provides me with information that I did not already know.') !!}
            <div class="radio">
                <label>{!! Form::radio('task_newinformation',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_newinformation',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_newinformation',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_newinformation',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_newinformation',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_newinformation',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_newinformation',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('task_unspecified','There are dimensions of the task that are not specified in the description.') !!}
            <div class="radio">
                <label>{!! Form::radio('task_unspecified',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_unspecified',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_unspecified',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_unspecified',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_unspecified',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_unspecified',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_unspecified',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('task_detail','The task description has a lot of details.') !!}
            <div class="radio">
                <label>{!! Form::radio('task_detail',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_detail',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_detail',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_detail',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_detail',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_detail',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_detail',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('task_knowspecific','Right now, I know some specific things to look for to address the task.') !!}
            <div class="radio">
                <label>{!! Form::radio('task_knowspecific',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowspecific',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowspecific',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowspecific',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowspecific',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowspecific',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_knowspecific',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('task_specificitems','The task is very specific in terms of the number of items I need to compare.') !!}
            <div class="radio">
                <label>{!! Form::radio('task_specificitems',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_specificitems',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_specificitems',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_specificitems',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_specificitems',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_specificitems',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_specificitems',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('task_factors','The task is very specific in terms of the factors I need to consider when comparing the items.') !!}
            <div class="radio">
                <label>{!! Form::radio('task_factors',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_factors',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_factors',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_factors',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_factors',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_factors',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('task_factors',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('queries_start','Right now, I know the specific terms and queries that I should use to start my search.') !!}
            <div class="radio">
                <label>{!! Form::radio('queries_start',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('queries_start',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('queries_start',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('queries_start',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('queries_start',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('queries_start',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('queries_start',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('know_usefulinfo','I know some specific types of useful information that I can obtain from search engine. (useful means useful for accomplishing the current search task)') !!}
            <div class="radio">
                <label>{!! Form::radio('know_usefulinfo',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('know_usefulinfo',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('know_usefulinfo',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('know_usefulinfo',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('know_usefulinfo',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('know_usefulinfo',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('know_usefulinfo',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>


            {!! Form::label('useful_notobtain','I know some specific types of useful information that I can NOT obtain from search engine.') !!}
            <div class="radio">
                <label>{!! Form::radio('useful_notobtain',1) !!}1 (Strongly disagree)</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('useful_notobtain',2) !!}2</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('useful_notobtain',3) !!}3</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('useful_notobtain',4) !!}4</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('useful_notobtain',5) !!}5</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('useful_notobtain',6) !!}6</label>
            </div>
            <div class="radio">
                <label>{!! Form::radio('useful_notobtain',7) !!}7 (Strongly agree)</label>
            </div>
            <br><br>

            <button type = "submit" class = "btn btn-success">Submit</button>

            <br><br>

            {!! Form::close() !!}
            
            





            </form>
            


        </div>

    </body>

</html>
