<?php

//This class contains the basic data that are inserted into the DB in most queries.
class Settings {
	private $homeURL;
	private $contactEmails = "study220@rutgers.edu";
//    private $homeDirectory = "userstudy2014";
    private $homeDirectory = "spring2015";
//	private $homeDirectory = "";
	private static $instance;

	public function __construct() {
		$this->homeURL = "http://".$_SERVER['HTTP_HOST']."/".$this->homeDirectory."/";   //$_SERVER['REQUEST_URI']; ///spring2012_affect/";
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

	//GETTERS
    public function getHomeURL()
	{
		return $this->homeURL;
	}

  public function getHomeDirectory()
	{
		return $this->homeDirectory;
	}

	public function getContactEmails(){
		return $this->contactEmails;
	}
	//SETTERS
    public function setHomeDirectory($homeDirectory)
	{
		$this->homeDirectory =  $homeDirectory;
	}
}
