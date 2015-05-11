<?php
error_reporting(E_ALL);
// Matt: Temporarily commented out to suppress errors
// require("config.php");
class Connection
{
	private static $instance;
	private $db_selected;
	private $link;
	private $lastID;

	public function __construct() {
		// $host = "localhost";
		// $username = "root";
		// $password = "";
		// $database = "coagmento-org";
		// Temp: commented out for debugging purposes
    $host = DB_HOST;
		$username = DB_USER;
		$password = DB_PASS;
		$database = DB_DATABASE;
		$this->link = mysqli_connect($host, $username, $password,$database) or die("Cannot connect to the database: ". mysql_error());
    $this->db_selected = mysqli_select_db($this->link,$database) or die ('Cannot connect to the database: ' . mysql_error());

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
			$results = mysqli_query($this->link,$query) or die($query . " ". mysql_error()); //TODO remove query
			$this->lastID = mysqli_insert_id($this->link);
			return $results;
		}
		catch(Exception $e){
			//echo $e->getMessage();
			//exit();
		}
	}

	public function esc($str){
		return mysqli_real_escape_string($this->link,$str);
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
		mysql_close($this->link);
	}

	/*public function __sleep()
	{
		return array('host', 'username', 'password', 'db');
	}*/

 }
?>
