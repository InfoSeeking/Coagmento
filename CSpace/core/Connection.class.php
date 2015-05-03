<?php
error_reporting(E_ALL);
class Connection
{
	private static $instance;
	private static $db_selected;
	private $link;
	private $lastID;

	public function __construct() {

		//Credentials for local machine
		/*$host = "localhost";
		$username = "root";
		$password = "root";
		$database = "userstudy_summer2014";*/

		//Credentials for server pilot database
		/*$host = "localhost";
		$username = "userstudy_pilot";
		$password = "sW,tur6";
		$database = "summer2014_userstudy_pilot";*/


		//Credentials for server real user study database
		/*$host = "localhost";
		$username = "userstudy_su14";
		$password = "Pvrke9.";
		$database = "summer2014_userstudy";
         */
        //Credentials for Matt's local test user study database - Fall 2014
        $host = "localhost";
		$username = "userstudy_sp15";
		$password = 'uu8H$baK#';
		$database = "spring2015_userstudy";



		$this->link = mysql_connect($host, $username, $password) or die("Cannot connect to the database: ". mysql_error());
        $this->db_selected = mysql_select_db($database, $this->link) or die ('Cannot connect to the database: ' . mysql_error());

	}

	public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

	public function commit($query)
	{
		try{
			$results = mysql_query($query) or die(" ". mysql_error());
			$this->lastID = mysql_insert_id();
			return $results;
		}
		catch(Exception $e){
			//echo $e->getMessage();
			//exit();
		}
	}

	public function esc($str){
		return mysql_real_escape_string($str);
	}

	public function getLastID()
	{
		return $this->lastID;
	}

	public function __wakeup()
	{
		$this->connect();
	}

	public function close()
	{
		mysql_close($link);
	}

	/*public function __sleep()
	{
		return array('host', 'username', 'password', 'db');
	}*/

 }
?>
