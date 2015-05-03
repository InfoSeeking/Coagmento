<?php
require_once('Connection.class.php');
require_once('Base.class.php');
require_once('Action.class.php');

class User extends Base{
  	protected $userName;
	protected $password;
	protected $firstName;
	protected $lastName;
	protected $organization;
	protected $email;
	protected $website;
	protected $code;
	protected $active;
	protected $joinDate;
	protected $lastLoginDate;
	protected $lastLoginTime;
	protected $loginCount;
	protected $lastActionTimestamp;
	protected $avatar;
	protected $color;
	protected $status;
	protected $points;
	protected $hear;
	protected $type;

	public function __User() {
		parent::__construct();
	}

  public static function getIDMap($projectID){
    $cxn = Connection::getInstance();
    $q = sprintf("SELECT userID, username FROM users WHERE projectID=%d", $projectID);
    $results = $cxn->commit($q);
    $map = array();
    while($row = mysql_fetch_assoc($results)){
      $map[$row["userID"]] = $row["username"];
    }
    return $map;
  }

	public function signUp()
	{
		/*$connection=Connection::getInstance();
		$query = "INSERT INTO coagmento.actions (userID, projectID, timestamp, date, time, action, value, ip) VALUES('$this->userID','$this->projectID','$this->timestamp','$this->date','$this->time','$this->actionName','$this->value','$this->ip')";
		$connection->commit($query);*/
	}

	public function login($userName, $password)
	{
		$connection=Connection::getInstance();
		//$query = "SELECT * FROM users WHERE username='$userName' AND password='$password' AND active=1";
		$query = "SELECT * FROM users WHERE username='$userName' AND password='$password'";

		$results = $connection->commit($query);

		if (mysql_num_rows($results)!=0) {
			$record = mysql_fetch_array($results, MYSQL_ASSOC);
			$this->userID = $record['userID'];
			$this->firstName = $record['firstName'];
			$this->lastName = $record['lastName'];
			$this->userName = $record['userName'];
            $action = new Action('login');
			$action->save();

			return true;
		}
		else
			return false;
	}

	//GETTERS
	public function getUserName()
	{
		return $this->userName;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function getOrganization()
	{
		return $this->organization;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getWebsite()
	{
		return $this->website;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function getActive()
	{
		return $this->active;
	}

	public function getJoinDate()
	{
		return $this->joinDate;
	}

	public function getLastLoginDate()
	{
		return $this->lastLoginDate;
	}

	public function getLastLoginTime()
	{
		return $this->lastLoginTime;
	}

	public function getLoginCount()
	{
		return $this->loginCount;
	}

	public function getLastActionTimestamp()
	{
		return $this->lastActionTimestamp;
	}

	public function getAvatar()
	{
		return $this->avatar;
	}

	public function getColor()
	{
		return $this->color;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getPoints()
	{
		return $this->points;
	}

	public function getHear()
	{
		return $this->hear;
	}

	public function getType()
	{
		return $this->type;
	}

	//SETTERS
	public function setUserName($userName)
	{
		$this->userName = $userName;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}

	public function setOrganization($organization)
	{
		$this->organization = $organization;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function setWebsite($website)
	{
		$this->website = $website;
	}

	public function setCode($code)
	{
		$this->code = $code;
	}

	public function setActive($active)
	{
		$this->active = $active;
	}

	public function setJoinDate($joinDate)
	{
		$this->joinDate = $joinDate;
	}

	public function setLastLoginDate($lastLoginDate)
	{
		$this->lastLoginDate = $lastLoginDate;
	}

	public function setLastLoginTime($lastLoginTime)
	{
		$this->lastLoginTime = $lastLoginTime;
	}

	public function setLoginCount($loginCount)
	{
		$this->loginCount = $loginCount;
	}

	public function setLastActionTimestamp($lastActionTimestamp)
	{
		$this->lastActionTimestamp = $lastActionTimestamp;
	}

	public function setAvatar($avatar)
	{
		$this->avatar = $avatar;
	}

	public function setColor($color)
	{
		$this->color = $color;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function setPoints($points)
	{
		$this->points = $points;
	}

	public function setHear($hear)
	{
		$this->hear = $hear;
	}

	public function setType($type)
	{
		$this->type = $type;
	}
}
?>
