<?php
require_once('Connection.class.php');
// Possible validators (currently using jQuery validation):
// Parsley: http://parsleyjs.org
// jQuery Validation: http://jqueryvalidation.org
// List: http://blog.revrise.com/web-form-validation-javascript-libraries/

// Documentation for questions
// Radio:
// {"options":{<name:value> pairs}}

// TODO: likertgroup question type - a set of questions under one heading (?).
// key: db key
// Rank order: variable number for rank order (current=3)
// Likert: variable number for Likert (current=5)
// What to do about registration information? We arguably want to put that in another table
// Account for different types of input format (addslashes, etc.)

class Questionnaires
{
	private static $instance;
	private $db_selected = "questionnaire_questions";
	private $questions;
	private $answers;
	private $suffix = "";
	private $basedirectory="";

	// Cache of questions

	public function __construct() {
		$this->questions = array();
		if(isset($_SESSION['Questionnaires_questions'])){
			$this->questions = $_SESSION['Questionnaires_questions'];
			// echo "SET!".(string)isset($_SESSION['Questionnaires_questions']);
		}else{
			// echo "NOT SET!".(string)isset($_SESSION['Questionnaires_questions']);
		}
		$_SESSION['Questionnaires_questions'] = $this->questions;



		$this->answers = array();
	}

	public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

		public function getQuestions(){
			return $this->questions;
		}
		public function setBaseDirectory($dir){
			$this->basedirectory = $dir;
		}

		public function populateQuestionsFromDatabase($questionnaire_name,$orderBy = NULL){
			$cxn = Connection::getInstance();
			$db_selected = $this->db_selected;

			$query = "SELECT * from $db_selected WHERE question_cat='$questionnaire_name'";
			if($questionnaire_name == 'spring2015'){
				$this->suffix="_1";
			}
			if($orderBy != NULL){
				$query .= " ORDER BY $orderBy";
			}
			$results = $cxn->commit($query);
			if (!$results){
				return false;
			}else{

				while($line = mysql_fetch_array($results,MYSQL_ASSOC)){
					$questionID = $line['questionID'];
					$question = $line['question'];
					$type = $line['question_type'];
					$data = json_decode($line['question_data']);
					$key = $line['key'];
					$this->addQuestion($questionID,$question,$type,$data,$key);
				}
				return true;
			}
		}

		public function isQuestionnaireComplete($questionnaire_name,$wherevals,$wherekeys=array('userID','projectID'),$answers_database=''){
			$n_questions = -1;
			$wherestr = "WHERE ";
			for($i = 0; $i < count($wherevals);$i+=1){
				$wherestr .= "".$wherekeys[$i]."='".(string)$wherevals[$i]."' AND ";
			}


			if ($answers_database != ''){
				$n_questions = 1;
				$wherestr = substr($wherestr,0,-4);
			}else{
				$n_questions = count($this->questions);
				$answers_database = $this->db_selected;
				$wherestr .= "question_cat='$questionnaire_name'";
			}

			$query = "SELECT * FROM $answers_database $wherestr";

			$cxn = Connection::getInstance();
			$results = $cxn->commit($query);
			$nrows = mysql_num_rows($results);
			$yes = mysql_num_rows($results) == $n_questions;
			return mysql_num_rows($results) == $n_questions;

		}

		public function addQuestion($questionID,$question,$type,$data,$key){
			array_push($this->questions,array(
				'questionID'=>$questionID,
				'question'=>$question,
				'question_type'=>$type,
				'question_data'=>$data,
				'key'=>$key
			));

			$_SESSION['Questionnaires_questions'] = $this->questions;
		}

		public function addQuestionAt($index){
			$to_insert = array(
				'questionID'=>$questionID,
				'question'=>$question,
				'question_type'=>$type,
				'question_data'=>$data,
				'key'=>$key
			);
			array_splice( $this->questions, $index, 0, $to_insert );
			$_SESSION['Questionnaires_questions'] = $this->questions;
		}

		public function addAnswer($key,$answer){
			$answer = addslashes($answer);
			foreach($this->questions as $v){
				if($v['key']==$key){
					$this->answers["$key"] = $answer;
					// echo "ANSWER ADDED!";
					return;
				}else if($v['question_type']=="rankedorder"){
					// echo "RANKED ORDER!!!";
					// print_r(json_encode($v['question_data']));
					// echo "ENDV";
					foreach($v['question_data']->{'options'} as $text=>$rokey){
						// echo "ROKEY: $rokey";
						if($rokey == $key){
							$this->answers["$key"] = $answer;
							// echo "ANSWER ADDED!";
							return;
						}
					}

				}
			}
		}

		public function clearCache(){
			$this->questions = array();
			$_SESSION['Questionnaires_questions'] = $this->questions;
			$this->answers = array();
		}

		public function popQuestion(){
			array_pop($this->question);
			$_SESSION['Questionnaires_questions'] = $this->questions;
		}

		public function removeQuestionAt($index){
			unset($this->question[$index]); // remove item at index 0
			$this->question = array_values($this->question); // 'reindex' array
			$_SESSION['Questionnaires_questions'] = $this->questions;
		}


		public function commitAnswersToDatabase($extravals,$extrakeys=array('userID','projectID'),$answers_database=''){

			// echo "ANSWERS:";print_r($this->answers);
			// echo "Questions:";print_r($this->questions);
			if($answers_database != ''){
				// echo "FIRST!";
				// If optional database given, commit: columns by key, respective column values, to given database
				$query = "INSERT INTO ";
				$keystr = "(";
				$valstr = "(";
				$query .= "$answers_database ";
				for ($i = 0; $i < count($extrakeys);$i++){
					$k = $extrakeys[$i];
					$v = $extravals[$i];
					$keystr .= "$k,";
					$valstr .= "'$v',";
				}

				foreach($this->answers as $anskey=>$ansval){
					$k = $anskey;
					$v = $ansval;
					$keystr .= "$k,";
					$valstr .= "'$v',";
				}

				$base = Base::getInstance();
				$time = $base->getTime();
				$date = $base->getDate();
				$timestamp = $base->getTimestamp();

				$keystr .= "`time`,";
				$valstr .= "'$time',";
				$keystr .= "`date`,";
				$valstr .= "'$date',";
				$keystr .= "`timestamp`,";
				$valstr .= "'$timestamp',";

				$keystr = rtrim($keystr,",");
				$valstr = rtrim($valstr,",");
				$keystr .= ")";
				$valstr .= ")";
				$query .= "$keystr VALUES $valstr";
				// echo "$query";
				$cxn = Connection::getInstance();
				return $cxn->commit($query);
			}else{
				// echo "SECOND!";
				// Default things to commit to database: userID, projectID, questionID, answer
				foreach($this->answers as $anskey=>$ansval){
					// Please specify userID,projectID

					$query = "INSERT INTO ";
					$keystr = "(";
					$valstr = "(";
					$query .= $this->db_selected." ";
					for ($i = 0; $i < count($extrakeys);$i++){
						$k = $extrakeys[$i];
						$v = $extravals[$i];
						$keystr .= "$k,";
						$valstr .= "'$v',";
					}

					$keystr .= "$anskey,";
					$valstr .= "'$ansval',";


					$base = Base::getInstance();
					$time = $base->getTime();
					$date = $base->getDate();
					$timestamp = $base->getTimestamp();

					$keystr .= "`time`,";
					$valstr .= "'$time',";
					$keystr .= "`date`,";
					$valstr .= "'$date',";
					$keystr .= "`timestamp`,";
					$valstr .= "'$timestamp',";


					$keystr = rtrim($keystr,",");
					$valstr = rtrim($valstr,",");
					$keystr .= ")";
					$valstr .= ")";
					$query .= "$keystr VALUES $valstr";
					// echo "$query";
					$cxn = Connection::getInstance();
					return $cxn->commit($query);

				}

			}

		}
		public function printQuestions($min=-1,$max=INF){
			if($min==-1){
				$min = 0;
			}else{
				$min = max(array($min,0));

			}
			if($max==INF){
				$max = count($this->questions)-1;
			}else{
				$max = min(array($max,count($this->questions)-1));

			}
			for($i = $min; $i <=$max; $i++){
				$q = $this->questions[$i];
				if($q['question_type']=='select'){
					$this->printSelect($q['question'],$q['key'],$q['question_data']);
				}else if($q['question_type']=='radio'){
					$this->printRadio($q['question'],$q['key'],$q['question_data']);
				}else if($q['question_type']=='rankedorder'){
					$this->printRankedOrder($q['question'],$q['key'],$q['question_data']);
				}else if($q['question_type']=='likert'){
					$this->printLikert($q['question'],$q['key'],$q['question_data']);
				}else if($q['question_type']=='text'){
					$this->printText($q['question'],$q['key'],$q['question_data']);
				}
				if($q['question_type']!='select'){
					echo "<br>";
				}

			}
		}

		public function printPreamble(){
			//Prints <link rel= ...>
			echo "<link rel=\"stylesheet\" href=\"".$this->basedirectory."study_styles/pure-release-0.5.0/buttons.css\">";
			echo "<link rel=\"stylesheet\" href=\"".$this->basedirectory."study_styles/pure-release-0.5.0/forms.css\">";
		  echo "<link rel=\"stylesheet\" href=\"".$this->basedirectory."study_styles/pure-release-0.5.0/grids-min.css\">";
			echo "<script src=\"".$this->basedirectory."assets/js/jquery-2.1.3.min.js\"></script>";
			echo "<script src=\"".$this->basedirectory."assets/js/validation/jquery-validation-1.13.1/dist/jquery.validate.js\"></script>";
			echo "<script src=\"".$this->basedirectory."assets/js/validation/validation.js\"></script>";
		}

		public function printPostamble(){
			echo "";
		}

		public function printValidation($formid,$rules="",$messages=""){
			// jQUery.validator.addMethod
			$suffix = $this->suffix;
			echo "jQuery.validator.addMethod(\"rankedorder\", function(value, element) {
					return isRankedOrderValid(value);}, \"<span style='color:red'>Please specify a ranked order according to the description above.</span>\");";
			echo "\$().ready(function(){";
				echo "\$(\"#$formid\").validate({";
					// Ignore none
					echo "ignore:\"\",\n";
					// Rules
					echo "rules: {";
					echo $rules;
					for($i = 0; $i <=count($this->questions)-1; $i++){
						$q = $this->questions[$i];
						$type = $q['question_type'];
						$key = $q['key'];
						if($type == 'radio'){
							echo "$key"."$suffix :{required: true}";
							if($i != count($this->questions)-1){
								echo ",";
							}
							echo "\n";
						}else if($type == 'select'){
							echo "$key"."$suffix :{required: true}";
							if($i != count($this->questions)-1){
								echo ",";
							}
							echo "\n";
						}else if($type == 'likert'){
							echo "$key"."$suffix :{required: true}";
							if($i != count($this->questions)-1){
								echo ",";
							}
							echo "\n";
						}else if($type == 'rankedorder'){
							echo "$key"."_div_key :{rankedorder: true}";
							if($i != count($this->questions)-1){
								echo ",";
							}
							echo "\n";
						}else if($type == 'text'){
							echo "$key"."_div_key :{required: true}";
							if($i != count($this->questions)-1){
								echo ",";
							}
							echo "\n";
						}

					}
					echo "},";
					// Messages

					echo "messages: {";
					echo $messages;
					for($i = 0; $i <=count($this->questions)-1; $i++){
						$q = $this->questions[$i];
						$type = $q['question_type'];
						$key = $q['key'];
						if($type == 'radio'){
							echo "$key"."$suffix :{required: \"<span style='color:red'>Please select an option.</span>\"}";
							if($i != count($this->questions)-1){
								echo ",";
							}
							echo "\n";
						}else if($type == 'select'){
							echo "$key"."$suffix :{required: \"<span style='color:red'>Please select an option.</span>\"}";
							if($i != count($this->questions)-1){
								echo ",";
							}
							echo "\n";
						}else if($type == 'likert'){
							echo "$key"."$suffix :{required: \"<span style='color:red'>Please select an option.</span>\"}";
							if($i != count($this->questions)-1){
								echo ",";
							}
							echo "\n";
						}else if($type == 'text'){
							echo "$key"."$suffix :{required: \"<span style='color:red'>Please enter your response.</span>\"}";
							if($i != count($this->questions)-1){
								echo ",";
							}
							echo "\n";
						}
					}
					echo "},";
					// Extra

				echo "			errorPlacement: function(error, element)
    			{
        if ( element.is(\":radio\") )
        {
            error.appendTo( element.parents('.container') );
        }
        else
        { // This is the default behavior
            error.insertAfter( element );
        }
    		}";

				echo "});";

				if($suffix=="_1"){
				echo "var doneyes = $(\"#doneproj$suffix-Yes\");
				var doneno = $(\"#doneproj$suffix-No\");
				var initial = doneyes.is(\":checked\");
				doneyes.click(function(){
					document.getElementById('experience_satisfaction$suffix"."_div').style.display=\"block\";
					document.getElementById('outcome_satisfaction$suffix"."_div').style.display=\"block\";
					document.getElementById('experience_satisfaction$suffix').disabled=false;
					document.getElementById('outcome_satisfaction$suffix').disabled=false;
				});
				doneno.click(function(){
					document.getElementById('experience_satisfaction$suffix"."_div').style.display=\"none\";
					document.getElementById('outcome_satisfaction$suffix"."_div').style.display=\"none\";
					document.getElementById('experience_satisfaction$suffix').disabled=true;
					document.getElementById('outcome_satisfaction$suffix').disabled=true;
				});
				document.getElementById('experience_satisfaction$suffix"."_div').style.display=\"none\";
				document.getElementById('experience_satisfaction$suffix"."_div').style.paddingLeft=\"60px\";
				document.getElementById('experience_satisfaction$suffix"."_div').style.backgroundColor=\"#F2F2F2\";
				document.getElementById('outcome_satisfaction$suffix"."_div').style.display=\"none\";
				document.getElementById('outcome_satisfaction$suffix"."_div').style.paddingLeft=\"60px\";
				document.getElementById('outcome_satisfaction$suffix"."_div').style.backgroundColor=\"#F2F2F2\";
				// style=\"display: none; padding-left:60px; background-color:#F2F2F2\"
				";
				}

			echo "});";


		}

		public function printRadio($question,$key,$data){
			$suffix = $this->suffix;

			echo "<div class=\"pure-control-group\">";
			echo "<label name=\"$key\">$question</label>";
			echo "<div id=\"$key"."_div$suffix\" class=\"container\">";
			echo "<label for=\"$key"."$suffix\" class=\"pure-radio\">";
			foreach($data->{'options'} as $optionkey=>$optionvalue){
				echo "<input id=\"$key"."$suffix-$optionvalue\" type=\"radio\" name=\"$key"."$suffix\" value=\"$optionvalue\"> $optionkey ";
			}
			echo "</label>";
			echo "</div>";
			echo "</div>";
		}

		public function printSelect($question,$key,$data){
			$suffix = $this->suffix;
			echo "<div class=\"pure-control-group\">\n";
			echo "<div id=\"$key"."$suffix"."_div\">";
			echo "<label name=\"$key"."$suffix\">$question</label>\n";
			echo "<select name=\"$key"."$suffix\" id=\"$key"."$suffix\" required>\n";
			echo "<option disabled selected>--Select one--</option>\n";
			foreach($data->{'options'} as $optionkey=>$optionvalue){
				echo "<option>$optionvalue</option>\n";
			}
			echo "</select>\n";
			echo "<br>\n";
			echo "</div>\n";
			echo "</div>\n\n";

		}

		public function printRankedOrder($question,$key,$data){
			$suffix = $this->suffix;

			echo "<label>$question</label>\n";
			echo "<div class=\"pure-form-aligned\">\n";
			echo "<input type=\"hidden\" name=\"$key"."_div_key\" value=\"$key"."_div\">";
			echo "<div id=\"$key"."_div\">\n";
			echo "<fieldset>\n";

			foreach($data->{'options'} as $q=>$k){
			  $pref = $k;
			  $description = $q;
			  echo "<div class=\"pure-control-group\" style=\"background-color:#F2F2F2\">\n";
			  echo "<label for=\"".$pref."$suffix\">$description</label>\n";
				// || (event.charCode >= 97 && event.charCode <= 99) //Append to end of onkeypress if necessary.
			  echo "<input id=\"".$pref."$suffix\" size=1 maxlength=\"1\" onkeypress='return (event.charCode < 47) || (event.charCode >= 49 && event.charCode <= 51)' name=\"".$pref."$suffix\" type=\"text\">\n";
			  echo "</div>\n";
			}
			echo "</fieldset>\n";
			echo "</div>\n";
			echo "</div>\n\n";

		}

		public function printLikert($question,$key,$data){
			$suffix = $this->suffix;
			$pref = $key;
			echo "<div style=\"border:1px solid gray; border-right-width:0px;border-left-width:0px\">\n";
			echo "<label \">$question</label>\n";
			echo "<div id=\"".$pref."_div$suffix\" class=\"container\">\n";
			echo "<div class=\"pure-g\">\n";
			$count = 1;
			foreach($data->{'options'} as $k=>$v){
				$style = "";
				if(($count)%2){
					$style = "style=\"background-color:#F2F2F2\"";
				}
				$countstr = "_$count";
				echo "<div $style class=\"pure-u-1-5\">";
				echo "<label for=\"".$pref."$suffix$countstr\" class=\"pure-radio\">";
				echo "<input id=\"".$pref."$suffix$countstr\" type=\"radio\" name=\"".$pref."$suffix\" value=\"$v\">$k";
				echo "</label>";
				echo "</div>\n";
				$count += 1;
			}
			echo "</div>\n";
			echo "</div>\n";
			echo "</div>\n\n";
		}

		public function printText($question,$key,$data){
			$suffix = $this->suffix;
			echo "<div class=\"pure-control-group\">\n";
			echo "<div id=\"$key"."$suffix"."_div\">";
			echo "<label name=\"$key"."$suffix\">$question</label>\n";
			echo "<textarea name=\"$key"."$suffix\" id=\"$key"."$suffix\" rows=\"3\" cols=\"40\" required></textarea>\n";
			echo "<br>\n";
			echo "</div>\n";
			echo "</div>\n\n";

		}


 }
?>
