<?php
date_default_timezone_set("America/Bogota");
session_start();

/* Conectar a la base de datos */
require_once("../modelos/conexion.php"); // Contiene función que conecta a la base de datos

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = Conexion::conectar();

    // $data = file_get_contents('php://input');
    // $data = json_decode($data, true);
    
    // // Convertir a JSON asegurando que no sea NULL
    // $aseguradora = $data['aseguradora'] ?? null;
    // $ofertas = $data['ofertas'] ?? null;
    // $cotizacion = $data['cotizacion'];
    // $mensajes = $data['mensajes'] ?? null;
    // $cotizo = $data['cotizo'] ?? null;
    // $fecha = date("Y-m-d H:i:s");

    $cotizacion = $_POST['cotizacion'] ?? null;
    $aseguradora = $_POST['aseguradora'] ?? null;
    $mensajes = $_POST['mensajes'] ?? null;
    $cotizo = $_POST['cotizo'] ?? null;
    $ofertas = $_POST['ofertas'] ?? null;
    $fecha = date("Y-m-d H:i:s");

    $stmt = $pdo->prepare("INSERT INTO alertas_hogar (id, cotizacion, aseguradora, mensajes, cotizo, num_ofertas ,fch_registro) 
                          VALUES (null, :cotizacion, :aseguradora, :mensajes, :cotizo, :ofertas, :fecha)");

    $stmt->bindParam(':cotizacion', $cotizacion);
    $stmt->bindParam(':aseguradora', $aseguradora);
    $stmt->bindParam(':mensajes', $mensajes);
    $stmt->bindParam(':cotizo', $cotizo);
    $stmt->bindParam(':ofertas', $ofertas);
    $stmt->bindParam(':fecha', $fecha);

    if ($stmt->execute()) {
        echo "Guardado correctamente";
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Error al guardar: " . $errorInfo[2];
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
