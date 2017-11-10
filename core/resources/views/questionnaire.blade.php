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
<body>
    
    <div class="bs-example">
        <div class="container">
            <div class="well">Please fill out this form as soon as possible</div>
        </div>
    </div>
</body>
            
            
            <form method="POST" action="/questionnaire">
                {{ csrf_field() }}


                <label for="sel1">Gender:</label>
                <div class="radio">
                    <label><input type="radio" name="gender" value="male">Male</label>
                </div>

                <div class="radio">
                    <label><input type="radio" name="gender" value="female">Female</label>
                </div>

                <br><br>

            <label for="sel1">Search Sources:</label>
                <div class="checkbox">
                    <label><input type="checkbox" name="searchSource[]" value="google">Google</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="searchSource[]" value="yahoo">Yahoo</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="searchSource[]" value="bing">Bing</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="searchSource[]" value="firefox">FireFox</label>
                </div>

                <br><br>

            <label for="sel1">Language Used:</label>
                <div class="form-group">
                    <input type="text" name="language">
                </div>

                <br><br>

            <label for="sel1">Descripe each search task you do on a daily basis:</label>
                <div class="form-group">
                    <textarea class="form-control" rows="5" name="searchTasks"></textarea>
                </div>

                <br><br>

<!--            <p>Select Your Year in College:</p>-->
            <div class="form-group">
                <label for="sel1">Select Year in College:</label>
                <select class="form-control" id="sel1" name="collegeYear">
                    <option disabled selected value> -- select an option -- </option>
                    <option>Freshman</option>
                    <option>Sophomore</option>
                    <option>Junior</option>
                    <option>Senior</option>
                </select>
                <br>
            </div>

            <div class="form-group">
                <label for="sel2">Mutiple select list (hold shift to select more than one):</label>
                <select multiple class="form-control" name="search_sources_v2[]">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>

            <br><br>


            <label for="sel1">Rate the difficulty level of the task:</label>
                <div class="row">
                    <div class="col-xs-1" style="background-color:lavender;">Not at all Difficult</div>
                    <div class="col-xs-1" style="background-color:lavender;">Somewhat Difficult</div>
                    <div class="col-xs-1" style="background-color:lavender;">Medium</div>
                    <div class="col-xs-1" style="background-color:lavender;">Very Difficult</div>
                    <div class="col-xs-1" style="background-color:lavender;">Extremely Difficult</div>
                </div>

                <div class="row">
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="task_difficulty" value="not_difficult"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="task_difficulty" value="somewhat_difficult"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="task_difficulty" value="medium"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="task_difficulty" value="very_difficult"></center>
                    </div>
                    <div class="col-xs-1" style="background-color:lightgray;"><center><input class="radio-inline" type="radio" name="task_difficulty" value="extremely_difficult"></center>
                    </div>
                </div>
                
            <br><br>

            <button type = "submit" class = "btn btn-success">Submit</button>
                
            <br><br>

            </form>
            
            @if(count($errors))
            <div class="alert alert-danger">
                <ul>

                    @foreach($errors->all() as $error)

                    <li>{{$error}}</li>

                    @endforeach

                </ul>

            </div>
            @endif

        </div>

    </body>

</html>
