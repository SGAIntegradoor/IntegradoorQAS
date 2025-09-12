<?php

class Conexion{

	public $dbname = 'grupoasi_cotizautos_dev';
	public $host = 'localhost:3307';

	public function __construct($dbname = null, $host = null){
		if($dbname != null){
			$this->dbname = $dbname;
		}
		if($host != null){
			$this->host = $host;
		}
	}

	public function setEnvironment($dbname, $host){
		$this->dbname = $dbname;
		$this->host = $host;
	}

	public static function getInstance($dbname = null, $host = null){
		static $instance = null;

		if ($instance === null) {
			$instance = new self($dbname);
			$instance->host = $host;
		}

		return $instance;
	}


	static public function conectar(){

		$URI = explode("/", $_SERVER['REQUEST_URI']);

		if (in_array("dev", $URI)) {
			self::getInstance()->setEnvironment('grupoasi_cotizautos_dev', 'localhost:3307');
		} elseif (in_array("QAS", $URI) || in_array("qas", $URI) || in_array("qas", $URI) || in_array("Pruebas", $URI)) {
			self::getInstance()->setEnvironment('grupoasi_cotizautos_qas', "localhost");
		} else {
			self::getInstance()->setEnvironment('grupoasi_cotizautos', "localhost");
		}

		$link = new PDO("mysql:host=". self::getInstance()->host .";dbname=". self::getInstance()->dbname,
			            "grupoasi_cotizautos",
			            'M1graci0n123');
		$link->exec("set names utf8");

		return $link;
	}
}