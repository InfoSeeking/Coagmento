<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            .row > div {
                outline: 2px solid #aaa;
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
    </head>



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
<body>
    
    <div class="bs-example">
        <div class="container">
            <div class="well">Please fill out this form as soon as possible</div>
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

            <form method="POST" action="/questionnaire_posttask">
                {{ csrf_field() }}

            <br><br>



                <label for="satisfaction">How satisfied was your search experience?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>1 (Very unsatisfied)</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>7 (Very satisfied)</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="satisfaction" name="satisfaction" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="satisfaction" name="satisfaction" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="satisfaction" name="satisfaction" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="satisfaction" name="satisfaction" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="satisfaction" name="satisfaction" value="5"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="satisfaction" name="satisfaction" value="6"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="satisfaction" name="satisfaction" value="7"></center>
                    </div>
                </div>
                <br><br>



                <label for="system_helpfulness">How well did the system help you in this task?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>1 (Very badly)</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>7 (Very well)</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="system_helpfulness" name="system_helpfulness" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="system_helpfulness" name="system_helpfulness" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="system_helpfulness" name="system_helpfulness" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="system_helpfulness" name="system_helpfulness" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="system_helpfulness" name="system_helpfulness" value="5"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="system_helpfulness" name="system_helpfulness" value="6"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="system_helpfulness" name="system_helpfulness" value="7"></center>
                    </div>
                </div>
                <br><br>


                <label for="goal_success">How well did you fulfill the goal of this task?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>1 (Very badly)</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>7 (Very well)</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="goal_success" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="goal_success" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="goal_success" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="goal_success" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="goal_success" value="5"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="goal_success" value="6"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="goal_success" value="7"></center>
                    </div>
                </div>
                <br><br>


                <label for="mental_demand">How mentally demanding was the search task?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>1 (Not demanding at all)</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>7 (Very demanding)</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="mental_demand" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="mental_demand" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="mental_demand" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="mental_demand" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="mental_demand" value="5"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="mental_demand" value="6"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="mental_demand" value="7"></center>
                    </div>
                </div>
                <br><br>


                <label for="physical_demand">How physically demanding was the search task?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>1 (Not at all demanding)</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>7 (Very demanding)</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="physical_demand" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="physical_demand" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="physical_demand" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="physical_demand" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="physical_demand" value="5"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="physical_demand" value="6"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="physical_demand" value="7"></center>
                    </div>
                </div>
                <br><br>


                <label for="temporal_demand">How hurried or rushed was the pace of the search task?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>1 (Not hurried or rushed at all)</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>7 (Very rushed)</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="temporal_demand" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="temporal_demand" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="temporal_demand" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="temporal_demand" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="temporal_demand" value="5"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="temporal_demand" value="6"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="temporal_demand" value="7"></center>
                    </div>
                </div>
                <br><br>


                <label for="effort">How hard did you have to work to accomplish your level of performance?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>1 (Not hard at all)</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>7 (Very hard)</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="effort" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="effort" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="effort" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="effort" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="effort" value="5"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="effort" value="6"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="effort" value="7"></center>
                    </div>
                </div>
                <br><br>


                <label for="frustration">How frustrated were you with this task?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>1 (Not frustrated at all)</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>7 (Very frustrated)</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="frustration" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="frustration" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="frustration" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="frustration" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="frustration" value="5"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="frustration" value="6"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="frustration" value="7"></center>
                    </div>
                </div>
                <br><br>



                <label for="difficulty_search">How difficult it was to search for information for this task using a search engine?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>Not at all difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Slightly difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Somewhat difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Moderately difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Very difficult</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_search" name="difficulty_search" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_search" name="difficulty_search" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_search" name="difficulty_search" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_search" name="difficulty_search" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_search" name="difficulty_search" value="5"></center>
                    </div>
                </div>
                <br><br>


                <label for="difficulty_understand">How difficult it was to understand the information the search engine finds?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>Not at all difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Slightly difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Somewhat difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Moderately difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Very difficult</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_understand" name="difficulty_understand" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_understand" name="difficulty_understand" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_understand" name="difficulty_understand" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_understand" name="difficulty_understand" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_understand" name="difficulty_understand" value="5"></center>
                    </div>
                </div>
                <br><br>


                <label for="difficulty_usefulinformation">How difficult it was to decide if the information the search engine finds is useful for completing the task?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>Not at all difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Slightly difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Somewhat difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Moderately difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Very difficult</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_usefulinformation" name="difficulty_usefulinformation" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_usefulinformation" name="difficulty_usefulinformation" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_usefulinformation" name="difficulty_usefulinformation" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_usefulinformation" name="difficulty_usefulinformation" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_usefulinformation" name="difficulty_usefulinformation" value="5"></center>
                    </div>
                </div>
                <br><br>


                <label for="difficulty_integrate">How difficult it was to integrate the information the search engine finds?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>Not at all difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Slightly difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Somewhat difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Moderately difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Very difficult</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_integrate" name="difficulty_integrate" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_integrate" name="difficulty_integrate" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_integrate" name="difficulty_integrate" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_integrate" name="difficulty_integrate" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_integrate" name="difficulty_integrate" value="5"></center>
                    </div>

                </div>
                <br><br>


                <label for="difficulty_enoughinformation">How difficult it was to determine when you have enough information to finish the task?</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;"><center>Not at all difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Slightly difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Somewhat difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Moderately difficult</center></div>
                    <div class="col-xs-1" style="background-color:lavender;"><center>Very difficult</center></div>
                </div>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_enoughinformation" name="difficulty_enoughinformation" value="1"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_enoughinformation" name="difficulty_enoughinformation" value="2"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_enoughinformation" name="difficulty_enoughinformation" value="3"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_enoughinformation" name="difficulty_enoughinformation" value="4"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty_enoughinformation" name="difficulty_enoughinformation" value="5"></center>
                    </div>
                </div>
                <br><br>

                {{----}}

                {{--<label for="difficulty">Rate the difficulty level of the task:</label>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>1</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>7</center></div>--}}
                {{--</div>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty" name="difficulty" value="1"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty" name="difficulty" value="2"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty" name="difficulty" value="3"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty" name="difficulty" value="4"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty" name="difficulty" value="5"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty" name="difficulty" value="6"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id="difficulty" name="difficulty" value="7"></center>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<br><br>--}}
                {{--<label for="task_success">How successful were you in completing the search task?</label>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>1</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>7</center></div>--}}
                {{--</div>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='task_success' name="task_success" value="1"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='task_success' name="task_success" value="2"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='task_success' name="task_success" value="3"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='task_success' name="task_success" value="4"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='task_success' name="task_success" value="5"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='task_success' name="task_success" value="6"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='task_success' name="task_success" value="7"></center>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<br><br>--}}

                {{--<label for="enough_time">Did you have enough time to finish the task?</label>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>1</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>2</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>3</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>4</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>5</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>6</center></div>--}}
                    {{--<div class="col-xs-1" style="background-color:lavender;"><center>7</center></div>--}}
                {{--</div>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='enough_time' name="enough_time" value="1"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='enough_time' name="enough_time" value="2"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='enough_time' name="enough_time" value="3"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='enough_time' name="enough_time" value="4"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='enough_time' name="enough_time" value="5"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='enough_time' name="enough_time" value="6"></center>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" id='enough_time' name="enough_time" value="7"></center>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{----}}
                {{--<br><br>--}}

            <button type = "submit" class = "btn btn-success">Submit</button>
                
            <br><br>

            </form>
            


        </div>

    </body>

</html>
