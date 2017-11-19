<?php namespace App\Services\Html;

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class FormBuilder extends \Collective\Html\FormBuilder {

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


		
		
		

}

