<?php
require_once('Connection.class.php');
require_once('Base.class.php');

class Action extends Base{
	protected $actionID;
	protected $actionName;
	protected $value;

	public function __construct($actionName, $value) {
		parent::__construct();
		$this->actionName = $actionName;
		$this->value = $value;
	}

	public function setBase(Base $base) {
		$this->date = $base->getDate();
		$this->time = $base->getTime();
		$this->timestamp = $base->getTimestamp();
		$this->userID = $base->getUserID();
		$this->projectID = $base->getProjectID();
		$this->ip = $base->getIP();
		$this->questionID = $base->getQuestionID();
		$this->localDate = $base->getLocalDate();
		$this->localTime = $base->getLocalTime();
		$this->localTimestamp = $base->getLocalTimestamp();
		$this->stageID = $base->getStageID();
	}

	public function save()
	{
		$query = "INSERT INTO actions (userID, projectID, timestamp, date, time, ip, action, value)
				  VALUES('".$this->getUserID()."','".$this->getProjectID()."','".$this->getTimestamp()."','".$this->getDate()."','".$this->getTime()."','".$this->getIP()."','$this->actionName','$this->value')";
		//echo "query: ".$query;
		$connection = Connection::getInstance();
		$connection->commit($query);
		$this->actionID = $connection->getLastID();
	}

	public function saveWithNewConnection(Connection $connection)
	{
		$query = "INSERT INTO actions (userID, projectID, stageID, questionID, timestamp, date, time, `localTimestamp`, `localDate`, `localTime`, ip, action, value)
				  VALUES('".$this->getUserID()."','".$this->getProjectID()."','".$this->getStageID()."','".$this->getQuestionID()."','".$this->getTimestamp()."','".$this->getDate()."','".$this->getTime()."','".$this->getLocalTimestamp()."','".$this->getLocalDate()."','".$this->getLocalTime()."','".$this->getIP()."','$this->actionName','$this->value')";
		//echo "query: ".$query;
		$connection->commit($query);
		$this->actionID = $connection->getLastID();
	}

	public static function retrieve($actionID)
	{
		$action = new Action();

		$query = "SELECT actionID, userID, projectID, timestamp, date, time, ip, action, value FROM actions WHERE actionID = $actionID";
		$connection = Connection::getInstance();
		$results = $connection->commit($query);

		if ($results = mysqli_fetch_array($results, MYSQL_ASSOC))
		{
			$action->setActionID($results['actionID']);
			$action->setUserID($results['userID']);
			$action->setProjectID($results['projectID']);
            $action->setTimestamp($results['timestamp']);
			$action->setDate($results['date']);
			$action->setTime($results['time']);
			$action->setIP($results['ip']);
			$action->setAction($results['action']);
			$action->setValue($results['value']);
			return $action;
		}

		return null;
	}

	//GETTERS
	public function getActionID()
	{
		return $this->actionID;
	}

	public function getAction()
	{
		return $this->action;
	}

	public function getValue()
	{
		return $this->value;
	}

	//SETTERS
	public function setActionID($actionID)
	{
		$this->actionID = $actionID;
	}

	public function setAction($actionName)
	{
		$this->actionName = $actionName;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}

	//TO STRING
	public function __toString()
	{
		return $this->actionID.",".$this->userID.",".$this->projectID.",".$this->timestamp.",".$this->date.",".$this->time.",".$this->ip.",".$this->actionName.",".$this->value;
	}
}
?>
