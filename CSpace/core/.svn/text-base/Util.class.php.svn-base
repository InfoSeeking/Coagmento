<?php
require_once('Stage.class.php');
require_once('Base.class.php');
require_once('Action.class.php');
require_once('Connection.class.php');
	
class Util
{
	private static $instance;
	
	public function __construct() {

	}
	
	public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
	
	public function moveToNextStage()
	{	
		$stage = new Stage();
		$stage->moveToNextStage();
		
		//echo $stage->getStageID();
		//echo "<br />";
		//echo $stage->getCurrentPage();
		//echo "<br />";
		
		$page = $stage->getCurrentPage();		
		header("Location: $page");		
	}
	
	public function moveToPreviousStage()
	{	
		$stage = new Stage();
		$stage->moveToPreviousStage();

		//echo $stage->getCurrentPage();
		//echo "<br />";
		
		$page = $stage->getCurrentPage();		
		header("Location: $page");		
	}
	
	public function checkCurrentPage($page)
	{	
		if (Base::getInstance()->isUserActive())
		{
			$stage = new Stage();
			
			if (($stage->getCurrentPage()<>$page)) //||($stage->getCurrentStage()<>Base::getInstance()->getStageID()))
			{
				$rightPage = $stage->getCurrentPage();
				header("Location: $rightPage");	
				return false;
			}
			return true;
		}
		else
			return false;
	}
	
	public function isRightPage($page)
	{	
		$base = Base::getInstance();
		$studyID = $base->getStudyID();
		if ($studyID==2)
		{
			return $this->checkCurrentPage($page);
		}
		else
		{
			$stage = new Stage();
			$result = ($stage->getCurrentPage()==$page);
			return ($stage->getCurrentPage()==$page);
		}
	}
	
	public function saveAction($action, $value, $base)
	{	
		$action = new Action($action,$value);
		$action->setBase($base);
		$action->save();
	}
	
	public function saveActionWithLocalTime($action, $value, $base, $localTime, $localDate, $localTimestamp)
	{	
		$action = new Action($action,$value);
		$action->setBase($base);
		$action->setLocalDate($localDate);
		$action->setLocalTime($localTime);
		$action->setLocalTimestamp($localTimestamp);
		$action->save();
	}
		
	public function checkSession()
	{	
		if (!Base::getInstance()->isSessionActive())
			header("Location: ../index.php");
		else
		{
			//Update Base
			$stage = new Stage();
			Base::getInstance()->setStageID($stage->getCurrentStage());
			Base::getInstance()->setMaxTime($stage->getMaxTime());	
			Base::getInstance()->setMaxTimeQuestion($stage->getMaxTimeQuestion());	
		}
	}	
	
	public function isSynchronized()
	{
		$base = Base::getInstance();
		$studyID = $base->getStudyID();
		
		if ($studyID==1) //If study is collaborative then check for synch; otherwise return true
		{
			$query = "SELECT 1
					  FROM session_progress
					  WHERE stageID = ".$base->getStageID()."
					    AND EXISTS (SELECT 1 FROM session_stages WHERE stageID = ".$base->getStageID()." AND synchStage = 1)				  
  					   AND projectID = '".$base->getProjectID()."'";

			//echo $query;
			
			$connection = Connection::getInstance();
			$results = $connection->commit($query);	
			$numRows = mysql_num_rows($results);
			
			if ($numRows==0)
				return -1;  //wrong stage
			else			
				return ((((int)$numRows)%2)==0); // 0: if not synch; 
			
			
			//echo $query;
			//echo "<br />";
			//echo "mod: ".$mod."<br />";
			//return (($mod==0)&&($numRows!=0));
			//	return true;
			//else
			//	return false;
			//Check for group of two.
			//return ($numRows==2); //((((int)$numRows)%2)==0); //($numRows==2); //(($numRows%2)==0);
		}
		else
			return -1; //Single study
	}

 }
?>