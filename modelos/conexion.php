<?php

class Conexion{

	public $dbname = 'grupoasi_cotizautos_dev';

	public function __construct($dbname = null){
		if($dbname != null){
			$this->dbname = $dbname;
		}
	}

	public function setEnvironment($dbname){
		$this->dbname = $dbname;
	}

	public static function getInstance($dbname = null){
		static $instance = null;

		if ($instance === null) {
			$instance = new self($dbname);
		}

		return $instance;
	}


	static public function conectar(){

		$URI = explode("/", $_SERVER['REQUEST_URI']);

		if (in_array("dev", $URI)) {
			self::getInstance()->setEnvironment('grupoasi_cotizautos_dev');
		} elseif (in_array("QAS", $URI) || in_array("qas", $URI) || in_array("qas", $URI) || in_array("Pruebas", $URI)) {
			self::getInstance()->setEnvironment('grupoasi_cotizautos_qas');
		} else {
			self::getInstance()->setEnvironment('grupoasi_cotizautos');
		}

		$link = new PDO("mysql:host=52.15.158.65;dbname=". self::getInstance()->dbname,
			            "grupoasi_cotizautos",
			            'M1graci0n123');
		$link->exec("set names utf8");

		return $link;
	}
}