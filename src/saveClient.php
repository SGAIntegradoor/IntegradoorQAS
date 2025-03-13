<?php

session_start();

/* Conectar a la base de datos */
require_once("../modelos/conexion.php"); // Contiene función que conecta a la base de datos

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = Conexion::conectar();
    $data = $_POST ?? null;
    // {
    //     "tipoDocumento": "C",
    //     "documento": "1151946527",
    //     "nombreCompleto": "Daniel Ospina Ramirez",
    //     "enabled": 1,
    //     "fechaNacimiento": "0000-00-00",
    //     "genero": 1,
    //     "estadoCivil": 1,
    //     "telefono": "3000000000",
    //     "email": "",
    //     "codigoCliente": ""
    // }

    $cli_codigo = "";

    $stmtCli = Conexion::conectar()->prepare("SELECT cli_codigo FROM clientes ORDER BY id_cliente DESC LIMIT 1");
    $stmtCli->execute();
    
    if ($stmtCli->rowCount() <= 1) {
        $row = $stmtCli->fetch();
        $cod = substr($row[0], 4);
        $cli_codigo = "CLI-" . ($cod + 1);
    } else {
        $cli_codigo = "CLI-1";
    }
    
    $data["codigoCli"] = $cli_codigo;
    
    $cli_codigo = $data['codigoCli'] ?? null;
    $cli_num_documento = $data['documento'] ?? null;
    $cli_nombre = $data['nombre'] ?? null;
    $cli_apellidos = $data['apellidos'] ?? null;
    $cli_fch_nacimiento = $data['fechaNacimiento'] ?? null;
    $cli_genero = $data['genero'] ?? null;
    $cli_telefono = $data['telefono'] ?? null;
    $cli_email = $data['email'] ?? null;
    $cli_estado = $data['enabled'] ?? null;
    $id_tipo_documento = $data['tipoDocumento'] ?? null;
    $id_estado_civil = $data['estadoCivil'] ?? null;


    $intermediario = $_SESSION["intermediario"];

    $stmt = $pdo->prepare("INSERT INTO clientes (id, cli_codigo, cli_num_documento, digitoVerificacion, cli_nombre, cli_apellidos, cli_fch_nacimiento, cli_genero, cli_telefono, cli_email, cli_estado, id_tipo_documento, id_estado_civil, id_Intermediario) 
                          VALUES (null, :cli_codigo, :cli_num_documento, :digitoVerificacion, :cli_nombre, :cli_apellidos, :cli_fch_nacimiento, :cli_genero, :cli_telefono, :cli_email, :cli_estado, :id_tipo_documento, :id_estado_civil, :id_Intermediario)");

    if ($stmt->execute()) {
        echo "Guardado correctamente";
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Error al guardar: " . $errorInfo[2];
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
