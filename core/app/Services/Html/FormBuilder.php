<?php namespace App\Services\Html;

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class FormBuilder extends \Collective\Html\FormBuilder {

		public function createhtml($questpromt = "please fill out the form"){
			return "<html lang='en'>
       <head>
          <title>Bootstrap Example</title>
           <meta charset='utf-8'>
     <meta name='viewport' content='width=device-width, initial-scale=1'>
     <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
     <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
     <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	  </head>
        <div class='container'>

            <h2>Questionnaire</h2>

<body>

    <div class='bs-example'>
        <div class='container'>
            <div class='well'>". $questpromt . "</div>
        </div>
    </div>

			";


		}
		public function openFormGroup(){
				return "<div class = 'form-group'>";

			}
		public function closeFormGroup(){

				return "</div>";
			}
		public function gender(){
				
			 	$x = "what is your gender?" . "<br>". $this->openFormGroup()  . $this->radioLabel("gender","male") . "<br></br>"   . $this->radioLabel('gender','female') . $this->closeFormGroup(); 
				return ($x);


			}
		public function radio($name, $value = null, $checked = null, $options = []){
				$options['id'] = $name. (isset($options['id']) ? ' ' . $options['id'] : '');
		
				 return parent::radio($name,$value,$checked,$options);

			}
		public function radioLabel($name,$value=null,$checked = null,$options = []){
				$htmlbuild = $this->radio($name,$value,$checked,$options) . $this->label($value,$value);
				return $htmlbuild;	

		}
		public function likert(){
				$htmlbuild = "LikeRT" . "<br>". $this->radio("likert","1") . "1" . "    " . $this->radio("likert","2") . "2" . "   ". $this->radio("likert","3") . "3" . "       " . $this->radio("likert","4") . "4" ."     ". $this->radio("likert","5") . "5";
				return($htmlbuild);

			}
		public function multiplechoice($question,$choice1,$choice2,$choice3,$choice4){
				$b = "<br>" .$question . "<br>" . $this ->radio($question,$choice1) . "    " . $choice1 . "<br>" . $this ->radio($question,$choice2) . "    " . $choice2 . "<br>" . $this ->radio($question,$choice3) . "    " . $choice3 . "<br>" . $this ->radio($question,$choice4) . "   ". $choice4; 
				return $b;
			

		}
		
		public function smallanswer($question){
				$htmlbuild = "<br>". $question . $this->text($question);
				return $htmlbuild;
				

			}


	public function openContainer(){
			$b = "<div class = 'container'>";
			return $b;
		

	}		

	public function closeDiv(){

		return "</div>";
	}
		

}

