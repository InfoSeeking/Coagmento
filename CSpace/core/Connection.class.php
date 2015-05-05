<?php
error_reporting(E_ALL);
require("config.php");
class Connection
{
	private static $instance;
	private static $db_selected;
	private $link;
	private $lastID;

	public function __construct() {
    $host = DB_HOST;
		$username = DB_USER;
		$password = DB_PASS;
		$database = DB_DATABASE;
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
			$results = mysql_query($query) or die($query . " ". mysql_error()); //TODO remove query
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
