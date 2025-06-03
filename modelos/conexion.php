<?php

class Conexion{

	static public function conectar(){
		// $link = new PDO("mysql:host=localhost;dbname=grupoasi_cotizautos",
		//  	            "root",
		//  	            "");
		// $link = new PDO("mysql:host=localhost:3307;dbname=grupoasi_cotizautos_qas",
		// 	            "grupoasi_cotizautos",
		// 	            "M1graci0n123");
		$link = new PDO("mysql:host=52.15.158.65;dbname=grupoasi_cotizautos_dev",
			            "grupoasi_cotizautos",
			            'M1graci0n123');
		$link->exec("set names utf8");

		return $link;
	}
}