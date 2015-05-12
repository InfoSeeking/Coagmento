<?php

    require_once('Connection.class.php');
    require_once('Stage.class.php');


//This class contains the basic data that are inserted into the DB in most queries.

    //PLEASE DELETE GETTER AND SETTER TO CHECK WHETHER COAGMENTO IS INSTALLED
    //(SEARCH FOR "TEMP" or "Matt" IN COMMENTS)
class Base {
	protected $userName;
	protected $userID;
	protected $projectID;
	protected $stageID;
	protected $questionID;
	protected $question;
	protected $studyID;
	protected $condition;
	protected $previousStageStartTimestamp;
	protected $taskStartTimestamp;
	protected $questionStartTimestamp;
	protected $page;
	protected $maxTime;
	protected $previousMaxTime;
	protected $timestamp;
	protected $date;
	protected $time;
	protected $ip;
	protected $localTimestamp = null;
	protected $localDate = null;
	protected $localTime = null;
	protected $maxTimeQuestion;
	protected $maxLoops;
	protected $currentLoops;
	protected $allowBrowsing;
	protected $allowCommunication;
	protected $remainingTimeStage78;
	protected $remainingTimeStage80;
	private static $instance;

    //TEMP (Matt)
    protected $topicAreaID;
    protected $peerPadID1;
    protected $peerPadID2;

    //END TEMP


    //TEMP (Matt).  For Simon's task
    const TASK_CIS = 1;
    const TASK_MDP = 2;


	public function __construct() {
        //TEMP FIX: Set all variables initialized to $_SESSION variables to NULL, if $_SESSION variable is not set
		date_default_timezone_set('America/New_York');

		$this->userName = NULL;
        if(isset($_SESSION['CSpace_userName'])){
            $this->userName = $_SESSION['CSpace_userName'];
        }


		$this->userID = NULL;
        if(isset($_SESSION['CSpace_userID'])){
            $this->userID = $_SESSION['CSpace_userID'];
        }

		$this->projectID = NULL;
        if(isset($_SESSION['CSpace_projectID'])){
            $this->projectID = $_SESSION['CSpace_projectID'];
        }

		$this->stageID = NULL;
        if(isset($_SESSION['CSpace_stageID'])){
            $this->stageID = $_SESSION['CSpace_stageID'];
        }

		$this->questionID = NULL;
        if(isset($_SESSION['CSpace_questionID'])){
            $this->questionID = $_SESSION['CSpace_questionID'];

        }

		$this->question = NULL;
        if(isset($_SESSION['CSpace_question'])){
            $this->question = $_SESSION['CSpace_question'];
        }

		$this->studyID = NULL;
        if(isset($_SESSION['CSpace_studyID'])){
            $this->studyID = $_SESSION['CSpace_studyID'];
        }

		$this->condition = NULL;
        if(isset($_SESSION['CSpace_condition'])){
            $this->condition = $_SESSION['CSpace_condition'];
        }

		$this->previousStageStartTimestamp = NULL;
        if(isset($_SESSION['CSpace_previousStageStartTimestamp'])){
            $this->previousStageStartTimestamp = $_SESSION['CSpace_previousStageStartTimestamp'];
        }

		$this->taskStartTimestamp = NULL;
        if(isset($_SESSION['CSpace_taskStartTimestamp'])){
            $this->taskStartTimestamp = $_SESSION['CSpace_taskStartTimestamp'];
        }

		$this->questionStartTimestamp = NULL;
        if(isset($_SESSION['CSpace_questionStartTimestamp'])){
            $this->questionStartTimestamp = $_SESSION['CSpace_questionStartTimestamp'];
        }

		$this->page = NULL;
        if(isset($_SESSION['CSpace_page'])){
            $this->page = $_SESSION['CSpace_page'];
        }

		$this->maxTime = NULL;
        if(isset($_SESSION['CSpace_maxTime'])){
            $this->maxTime = $_SESSION['CSpace_maxTime'];
        }

		$this->previousMaxTime = NULL;
        if(isset($_SESSION['CSpace_previousMaxTime'])){
            $this->previousMaxTime = $_SESSION['CSpace_previousMaxTime'];
        }

		$this->maxTimeQuestion = NULL;
        if(isset($_SESSION['CSpace_maxTimeQuestion'])){
            $this->maxTimeQuestion = $_SESSION['CSpace_maxTimeQuestion'];
        }

		$this->maxLoops = NULL;
        if(isset($_SESSION['CSpace_maxLoops'])){
            $this->maxLoops = $_SESSION['CSpace_maxLoops'];
        }

		$this->currentLoops = NULL;
        if(isset($_SESSION['CSpace_currentLoops'])){
            $this->currentLoops = $_SESSION['CSpace_currentLoops'];
        }

		$this->allowBrowsing = NULL;
        if(isset($_SESSION['CSpace_allowBrowsing'])){
            $this->allowBrowsing = $_SESSION['CSpace_allowBrowsing'];
        }

		$this->allowCommunication = NULL;
        if(isset($_SESSION['CSpace_allowCommunication'])){
            $this->allowCommunication = $_SESSION['CSpace_allowCommunication'];
        }


		$this->timestamp = time();
		$datetime = getdate();
		$this->date = date('Y-m-d', $datetime[0]);
		$this->time = date('H:i:s', $datetime[0]);
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->remainingTimeStage78 = 0;
		$this->remainingTimeStage80= 0;
	}

	public function __destructor() {

	}

	public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }


    //TEMP: Check for whether coagmento is installed
    //ONLY FOR THIS USER STUDY:  PLEASE REMOVE IN FUTURE -> Matt
    public function isNoCoagmento(){
        return isset($_SESSION['no_coagmento']);
    }

    public function setNoCoagmento(){
        $_SESSION['no_coagmento'] = 1;
    }
    //END TEMP

  public function registerActivity(){
    $_SESSION["LAST_ACTIVE"] = time();
  }

  public function checkTimeout(){
    //currently thirty minutes
    if($this->isSessionActive() && isset($_SESSION["LAST_ACTIVE"]) && time() - $_SESSION["LAST_ACTIVE"] > 1800){
      $this->setAllowCommunication(0);
      $this->setAllowBrowsing(0);
      session_destroy();
    } else {
      return false;
    }
  }

	//GETTERS
    public function isUserActive()
	{
		return isset($_SESSION['CSpace_userID']);
	}

	public function getUserName()
	{
		return $this->userName;
	}

	public function getUserID()
	{
		return $this->userID;
	}

	public function getProjectID()
	{
		return $this->projectID;
	}
  public function getAllProjects(){
    $cxn = Connection::getInstance();
    $q = sprintf("select * from projects P, memberships M where P.projectID=M.projectID and M.userID=%d", $this->userID);
    $results = $cxn->commit($q);
    return $results;
  }

	public function getStageID()
	{
		return $this->stageID;
	}

	public function getPage()
	{
		return $this->page;
	}

	public function getTimestamp()
	{
		return $this->timestamp;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function getTime()
	{
		return $this->time;
	}

	public function getLocalTimestamp()
	{
		return $this->localTimestamp;
	}

	public function getLocalDate()
	{
		return $this->localDate;
	}

	public function getLocalTime()
	{
		return $this->localTime;
	}

	public function getIP()
	{
		return $this->ip;
	}

	public function getQuestionID()
	{
		return $this->questionID;
	}

	public function getQuestion()
	{
		return $this->question;
	}

	public function getStudyID()
	{
		return $this->studyID;
	}

	public function getCondition()
	{
		return $this->condition;
	}

	public function getTaskStartTimestamp()
	{
		return $this->taskStartTimestamp;
	}

	public function getPreviousStageStartTimestamp()
	{
		return $this->previousStageStartTimestamp;
	}

	public function getQuestionStartTimestamp()
	{
		return $this->questionStartTimestamp;
	}

	public function getMaxTime()
	{
		return $this->maxTime;
	}

	public function getPreviousMaxTime()
	{
		return $this->previousMaxTime;
	}

	public function getAllowCommunication()
	{
		 return $this->allowCommunication;
	}

	public function getMaxTimeQuestion()
	{
		return $this->maxTimeQuestion;
	}

	public function getMaxLoops()
	{
		return $this->maxLoops;
	}

	public function getCurrentLoops()
	{
		return $this->currentLoops;
	}

	public function getAllowBrowsing()
	{
		return $this->allowBrowsing;
	}

	public function getXML() {

		$xml = new XmlWriter();
		$xml->openMemory();
		$xml->startDocument('1.0');
		$xml->setIndent(true);

		$this->getObject2XML($xml, $this);
 		$xml->endElement();

 		return $xml->outputMemory(true);
	}

	private function getObject2XML(XMLWriter &$xml, $data) {
		foreach($data as $key => $value) {

			if(is_object($value)) {
				$xml->startElement($key);
				$this->getObject2XML($xml, $value);
				$xml->endElement();
				continue;
			}
			else if(is_array($value)) {
				$this->getArray2XML($xml, $key, $value);
			}

			if (is_string($value)) {
				$xml->writeElement($key, $value);
			}
		}
	}

	private function getArray2XML(XMLWriter $xml, $keyParent, $data) {
		foreach($data as $key => $value) {
			if (is_string($value)) {
				$xml->writeElement($keyParent, $value);
				continue;
			}

			if (is_numeric($key)) {
				$xml->startElement($keyParent);
			}

			if(is_object($value)) {
				$this->getObject2XML($xml, $value);
			}
			else if(is_array($value)) {
				$this->getArray2XML($xml, $key, $value);
				continue;
			}

			if (is_numeric($key)) {
				$xml->endElement();
			}
		}
	}

	//SETTERS
	public function setUserName($userName)
	{
		 $this->userName = $userName;
		 $_SESSION['CSpace_userName'] = $userName;

	}

	public function setAllowCommunication($allowCommunication)
	{
		 $this->allowCommunication = $allowCommunication;
		 $_SESSION['CSpace_allowCommunication'] = $allowCommunication;

	}

	public function setUserID($userID)
	{
		 $this->userID = $userID;
		 $_SESSION['CSpace_userID'] = $userID;

	}

  public function selectProject($projectID){
      // Update the selected project information for this user in the options table
      $userID = $this->userID;
      $query = "SELECT * FROM options WHERE userID='$userID' AND `option`='selected-project'";
      $connection = Connection::getInstance();
      $results = $connection->commit($query);
      if (mysqli_num_rows($results)==0) {
       $query = "INSERT INTO options VALUES('','$userID','$projectID','selected-project','$projectID')";
      }
      else {
       $query = "UPDATE options SET value='$projectID' WHERE userID='$userID' AND `option`='selected-project'";
      }
      $connection->commit($query);
  }

  public function getSelectedProject(){
    $connection = Connection::getInstance();
    $query = sprintf("SELECT `value` FROM options WHERE `option`='selected-project' AND userID='%s'", $this->userID);
    $results = $connection->commit($query);
    if (mysqli_num_rows($results) == 0){
      return -1;
    } else {
      $row = mysqli_fetch_assoc($results);
      return $row['value'];
    }
  }

	public function setProjectID($projectID)
	{
		$this->projectID = $projectID;
		$_SESSION['CSpace_projectID'] = $projectID;
	}

	public function setStageID($stageID)
	{
		 $this->stageID = $stageID;
		 $_SESSION['CSpace_stageID'] = $stageID;
	}

	public function setQuestionID($questionID)
	{
		 $this->questionID = $questionID;
		 $_SESSION['CSpace_questionID'] = $questionID;
	}

	public function setQuestion($question)
	{
		 $this->question = $question;
		 $_SESSION['CSpace_question'] = $question;
	}

	public function setStudyID($studyID)
	{
		 $this->studyID = $studyID;
		 $_SESSION['CSpace_studyID'] = $studyID;
	}

	public function setCondition($condition)
	{
		 $this->condition = $condition;
		 $_SESSION['CSpace_condition'] = $condition;
	}

	public function setPage($page)
	{
		 $this->page = $page;
		 $_SESSION['CSpace_page'] = $page;
	}

	public function setPreviousStartTimestamp($previousStartTimestamp)
	{
		 $this->previousStageStartTimestamp = $previousStartTimestamp;
		 $_SESSION['CSpace_previousStageStartTimestamp'] = $previousStartTimestamp;
	}

	public function setPreviousMaxTime($previousMaxTime)
	{
	 	$this->previousMaxTime = $previousMaxTime;
		$_SESSION['CSpace_previousMaxTime'] = $previousMaxTime;
	}

	public function setTaskStartTimestamp($taskStartTimestamp)
	{
		 $this->taskStartTimestamp = $taskStartTimestamp;
		 $_SESSION['CSpace_taskStartTimestamp'] = $taskStartTimestamp;
	}

	public function setQuestionStartTimestamp($questionStartTimestamp)
	{
		 $this->questionStartTimestamp = $questionStartTimestamp;
		 $_SESSION['CSpace_questionStartTimestamp'] = $questionStartTimestamp;
	}

	public function setTimestamp($timestamp)
	{
		$this->timestamp = $timestamp;
	}

	public function setDate($date)
	{
		$this->date = $date;
	}

	public function setTime($time)
	{
		$this->time = $time;
	}

	public function setLocalTimestamp($localTimestamp)
	{
		$this->localTimestamp = $localTimestamp;
	}

	public function setLocalDate($localDate)
	{
		$this->localDate = $localDate;
	}

	public function setLocalTime($localTime)
	{
		$this->localTime = $localTime;
	}

	public function setIP($ip)
	{
		$this->ip = $ip;
	}

	public function setMaxTime($maxTime)
	{
		//This is stage-dependent... just a temporary solution to provide overall time to the user in the stimuli stage
		 // if ($this->stageID==79)
		 // {
		 	// $_SESSION['CSpace_previousMaxTimeStage80'] = 600;

		 	// if ($this->getPreviousStageRemainingTime()>0)
		 	// {
		 		// $_SESSION['CSpace_remainingTimeStage78']=$this->getPreviousStageRemainingTime();
		 		// $_SESSION['CSpace_previousMaxTimeStage80'] += $this->getPreviousStageRemainingTime();
		 	// }
		 	// //$this->remainingTimeStage78=$this->getPreviousStageRemianingTime();
		 	// //echo "s78: ".$this->remainingTimeStage78;

		 // }

		 // //Every time users is in q_sam, the remaining time is saved so that it can be later added to the tim ein stage 91
		 // if ($this->stageID==90)
		 // {
		 	// if ($this->getPreviousStageRemainingTime()>0)
		 		// $_SESSION['CSpace_remainingTimeStage80']=$this->getPreviousStageRemainingTime();
		 	// //$this->remainingTimeStage80=$this->getPreviousStageRemianingTime();
		 	// //echo "s80: ".$this->remainingTimeStage80;
		 // }

		 // $this->maxTime = $maxTime;

		 // //Stage 80 will have the time assigned to this stage plus what remained from stage 78
		 // if ($this->stageID==80)
		 // {
		 	// $this->maxTime += $_SESSION['CSpace_remainingTimeStage78'];
		 	// //$this->remainingTimeStage80=$this->getPreviousStageRemianingTime();
		 	// //echo "s80: ".$this->remainingTimeStage80;
		 // }

		 $this->maxTime = $maxTime; // $maxTime;
		 $_SESSION['CSpace_maxTime'] = $maxTime; // $maxTime;
	}

	public function setMaxTimeQuestion($maxTimeQuestion)
	{
		 $this->maxTimeQuestion = $maxTimeQuestion;
		 $_SESSION['CSpace_maxTimeQuestion'] = $maxTimeQuestion;
	}

	public function setMaxLoops($maxLoops)
	{
		 $this->maxLoops = $maxLoops;
		 $_SESSION['CSpace_maxLoops'] = $maxLoops;
	}

	public function setCurrentLoops($currentLoops)
	{
		 $this->currentLoops = $currentLoops;
		 $_SESSION['CSpace_currentLoops'] = $currentLoops;
	}

	public function setAllowBrowsing($allowBrowsing)
	{
		 $this->allowBrowsing = $allowBrowsing;
		 $_SESSION['CSpace_allowBrowsing'] = $allowBrowsing;
	}

	public function isSessionActive()
	{
		return isset($_SESSION['CSpace_userID']);
	}

	public function isTaskActive()
	{
		return isset($_SESSION['CSpace_taskStartTimestamp']);
	}

	public function isValidLoop()
	{
		/*echo "Current Loops: ".$this->currentLoops;
		echo "<br />";
		echo "Max Loop: ".$this->maxLoops;
		echo "<br />";*/
		return ($this->currentLoops<$this->maxLoops);
	}

	public function isQuestionInTime() //UPPER LIMIT IN SECONDS e.g. 1800 for 30 mins
	{
		//$t2 = $this->getTimestamp(); //Current time
		//$t1 = $this->getQuestionStartTimestamp(); //Starting time
		//$elapsedTime = (int)$t2-(int)$t1;
		//$limit = $this->getMaxTimeQuestion();
		/*echo "<br /> Question";
		echo "<br /> t1: $t1";
		echo "<br /> t2: $t2";
		echo "<br /> el: $elapsedTime";
		echo "<br /> li: $limit";
		echo "<br />";
		return ((int)$elapsedTime<(int)$limit);*/
		return ($this->getQuestionRemainingTime()>0);

	}

	public function getQuestionRemainingTime()
	{
		$t2 = $this->getTimestamp(); //Current time
		$t1 = $this->getQuestionStartTimestamp(); //Starting time
		$elapsedTime = (int)$t2-(int)$t1;
		$limit = $this->getMaxTimeQuestion();
		return ($limit-$elapsedTime);
	}

	public function isTaskInTime() //UPPER LIMIT IN SECONDS e.g. 1800 for 30 mins
	{
		//$t2 = $this->getTimestamp(); //Current time
		//$t1 = $this->getTaskStartTimestamp(); //Starting time
		///$elapsedTime = (int)$t2-(int)$t1;
		//$limit = $this->getMaxTime();
		// echo "<br /> Task";
		// echo "<br /> ct: ".$this->getTimestamp();
		// echo "<br /> ts: ".$this->getTaskStartTimestamp();
		// echo "<br /> mx: ".$this->getMaxTime();
		// echo "<br /> li: ".$this->getTaskRemainingTime();
		// echo "<br />";
		//return ((int)$elapsedTime<(int)$limit);
		return ($this->getTaskRemainingTime()>0);
	}

	public function getTaskRemainingTime()
	{
		$t2 = $this->getTimestamp(); //Current time
		$t1 = $this->getTaskStartTimestamp(); //Starting time
		$elapsedTime = (int)$t2-(int)$t1;
		$limit = $this->getMaxTime();
		return ($limit-$elapsedTime);
	}

	public function getTimeStage80()
	{
		return $_SESSION['CSpace_previousMaxTimeStage80'];
	}

	public function isPreviousStageInTime() //UPPER LIMIT IN SECONDS e.g. 1800 for 30 mins
	{
		$t2 = $this->getTimestamp(); //Current time
		$t1 = $this->getPreviousStageStartTimestamp(); //Starting time
		$elapsedTime = (int)$t2-(int)$t1;
		$limit = $this->getPreviousMaxTime();
		if ($this->stageID==90)
			$limit = $_SESSION['CSpace_previousMaxTimeStage80'];
		/*echo "<br /> t1: $t1";
		echo "<br /> t2: $t2";
		echo "<br /> el: $elapsedTime";
		echo "<br /> li: $limit";
		echo "<br />";*/
		return ((int)$elapsedTime<(int)$limit);
	}

	public function getPreviousStageRemainingTime() //UPPER LIMIT IN SECONDS e.g. 1800 for 30 mins
	{
		$t2 = $this->getTimestamp(); //Current time
		$t1 = $this->getPreviousStageStartTimestamp(); //Starting time
		$elapsedTime = (int)$t2-(int)$t1;
		$limit = $this->getPreviousMaxTime();
		return ($limit-$elapsedTime);
	}


	public function getRemainingTimeStage78()
	{
		return $_SESSION['CSpace_remainingTimeStage78'];
	}

	public function getRemainingTimeStage80()
	{
		return $_SESSION['CSpace_remainingTimeStage80'];
	}

    //TEMP (Matt)
    public function getTopicAreaID(){
        if(!isset($this->topicAreaID)){
            $query = "SELECT * FROM users WHERE userID='".$this->userID."' ORDER BY topicAreaID DESC";
            $connection = Connection::getInstance();
            $results = $connection->commit($query);
            $line = mysqli_fetch_array($results, MYSQL_ASSOC);
            $this->topicAreaID = $line['topicAreaID']; //1: CIS  2: MDP
        }

        return $this->topicAreaID;

    }

    public function getQuestionIDReviewTask(){
        if(!isset($this->questionID)){
            $projectID = $this->getProjectID();
            $userID = $this->getUserID();
            $query = "SELECT Q.questionID as questionID FROM recruits R,questions_study Q WHERE R.projectID='$projectID' AND R.userID='$userID' AND R.instructorID+1=Q.questionID ORDER BY recruitsID ASC";
            $connection = Connection::getInstance();
            $results = $connection->commit($query);
            $line = mysqli_fetch_array($results, MYSQL_ASSOC);
            $this->setQuestionID($line['questionID']);
        }
        return $this->questionID;
    }

    private function isValidEtherpad($projectID){
        $data = '';
        $port = 9010;
        $apikey="";
        $topicAreaID = $this->getTopicAreaID();
        if($topicAreaID == Base::TASK_CIS){ //CIS
            $port = 9005;
            $apikey="857212484544558872d773276b65eba2d916510f2022c613e5e4517cc57d863c";
        }else{ //MDP
            $port = 9010;
            $apikey="857212484544558872d773276b65eba2d916510f2022c613e5e4517cc57d863c";
        }

        $padID = "edusearch2014_report-$projectID-".Stage::SESSION_ONE_MAIN_TASK."-".$this->getQuestionIDReviewTask()."";
        $url = "http://coagmentopad.rutgers.edu:".$port."/api/1/getText?apikey=".$apikey."&padID=".$padID;
        $data=file_get_contents($url);
        $data_str = $data;
        $data=json_decode($data);
        return $data->{'code'} == 0;
    }

    private function initializePeerPadIDs(){
        $query = "SELECT peerPadID1,peerPadID2 FROM users WHERE userID='".$this->userID."'";
        $connection = Connection::getInstance();
        $results = $connection->commit($query);
        $line = mysqli_fetch_array($results, MYSQL_ASSOC);

        if(is_null($line['peerPadID1'])){
            $query = "SELECT a.projectID,qer.pad_projectID,COUNT(qer.pad_projectID) as count
            FROM (SELECT projectID FROM users where projectID != '".$this->projectID."' AND topicAreaID='".$this->getTopicAreaID()."' AND projectID in
                  (SELECT projectID from session_progress WHERE stageID='".Stage::SESSION_ONE_MAIN_TASK."')
                  GROUP BY projectID) a LEFT JOIN (SELECT * from questionnaire_essay_review WHERE projectID!=pad_projectID) qer ON a.projectID=qer.pad_projectID
            GROUP BY a.projectID ORDER BY count ASC";
            $connection = Connection::getInstance();
            $results = $connection->commit($query);

            $min = -1;
            $pads = array();
            while($line = mysqli_fetch_array($results, MYSQL_ASSOC)){
                $id_to_push = $line['projectID'];
                if($min == -1){
                    $min = intval($line['count']);
                }
                if($min < intval($line['count']) && count($pads) >= 2){
                    break;
                }else if ($this->isValidEtherpad($id_to_push) > 0){
                    array_push($pads,$id_to_push);
                }
            }
            $rand_keys = array_rand($pads, 2);
            $this->peerPadID1 = $pads[$rand_keys[0]];
            $this->peerPadID2 = $pads[$rand_keys[1]];

            $query = "UPDATE users SET peerPadID1='".$this->peerPadID1."',peerPadID2='".$this->peerPadID2."' WHERE userID='".$this->userID."'";
            $connection = Connection::getInstance();
            $results = $connection->commit($query);
        }else{
            $this->peerPadID1 = $line['peerPadID1'];
            $this->peerPadID2 = $line['peerPadID2'];
        }
    }

    public function getPeerPadID1(){
        if(!isset($this->peerPadID1) || !isset($this->peerPadID2)){
            $this->initializePeerPadIDs();
        }
        return $this->peerPadID1;
    }

    public function getPeerPadID2(){
        if(!isset($this->peerPadID1) || !isset($this->peerPadID2)){
            $this->initializePeerPadIDs();
        }
        return $this->peerPadID2;
    }


    private function initializeDiagnosticPadIDs(){
        $query = "SELECT diagnosticPadID1,diagnosticPadID2,diagnosticPadID3 FROM users WHERE userID='".$this->userID."'";
        $connection = Connection::getInstance();
        $results = $connection->commit($query);
        $line = mysqli_fetch_array($results, MYSQL_ASSOC);

        if(is_null($line['diagnosticPadID1'])){

            $max = -1;
            $pad_tuples = array();
            for($x=1; $x <=3; $x++){
                for($y=1; $y <=3; $y++){
                    for($z=1; $z <=3; $z++){
                        if($x==$y || $x==$z || $y==$z){
                            continue;
                        }
                        $query = "SELECT diagnosticPadID1,COUNT(*) as count FROM users WHERE diagnosticPadID1='".$x."' AND diagnosticPadID2='".$y."' AND diagnosticPadID3='".$z."' GROUP BY diagnosticPadID1";
                        $connection = Connection::getInstance();
                        $results = $connection->commit($query);
                        $n_results = 0;
                        if(mysqli_num_rows($results) > 0){
                            $line = mysqli_fetch_array($results, MYSQL_ASSOC);
                            $n_results = $line['count'];
                        }

                        if($max == -1){
                            $max = $n_results;
                            array_push($pad_tuples,array($x,$y,$z));
                        }else if($max > $n_results){
                            $max = $n_results;
                            unset($pad_tuples);
                            $pad_tuples = array();
                            array_push($pad_tuples,array($x,$y,$z));
                        }else if ($max == $n_results){
                            array_push($pad_tuples,array($x,$y,$z));
                        }

                    }
                }
            }


            $rand_key = array_rand($pad_tuples, 1);
            $this->diagnosticPadID1 = $pad_tuples[$rand_key][0];
            $this->diagnosticPadID2 = $pad_tuples[$rand_key][1];
            $this->diagnosticPadID3 = $pad_tuples[$rand_key][2];

            $query = "UPDATE users SET diagnosticPadID1='".$this->diagnosticPadID1."',diagnosticPadID2='".$this->diagnosticPadID2."',diagnosticPadID3='".$this->diagnosticPadID3."' WHERE userID='".$this->userID."'";
            $connection = Connection::getInstance();
            $results = $connection->commit($query);
        }else{
            $this->diagnosticPadID1 = $line['diagnosticPadID1'];
            $this->diagnosticPadID2 = $line['diagnosticPadID2'];
            $this->diagnosticPadID3 = $line['diagnosticPadID3'];

        }
    }

    public function getDiagnosticPadID1(){
        if(!isset($this->diagnosticPadID1) || !isset($this->diagnosticPadID2) || !isset($this->diagnosticPadID3)){
            $this->initializeDiagnosticPadIDs();
        }
        return $this->diagnosticPadID1;
    }

    public function getDiagnosticPadID2(){
        if(!isset($this->diagnosticPadID1) || !isset($this->diagnosticPadID2) || !isset($this->diagnosticPadID3)){
            $this->initializeDiagnosticPadIDs();
        }
        return $this->diagnosticPadID2;
    }

    public function getDiagnosticPadID3(){
        if(!isset($this->diagnosticPadID1) || !isset($this->diagnosticPadID2) || !isset($this->diagnosticPadID3)){
            $this->initializeDiagnosticPadIDs();
        }
        return $this->diagnosticPadID3;
    }

}
?>
