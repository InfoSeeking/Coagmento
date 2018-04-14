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

            {!! Form::open(['url' => '/questionnaire_pretask']) !!}

            {{ csrf_field() }}

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

            <input type="hidden" name="user_id" value="999">
            <input type="hidden" name="stage_id" value="999">

            <button type = "submit" class = "btn btn-success">Submit</button>

            <br><br>

            {!! Form::close() !!}
            
            





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
