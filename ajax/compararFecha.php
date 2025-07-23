<?php
session_start();
require_once '../config/dbconfig.php';

$id = $_SESSION['idUsuario'] ?? null;
$intermediario = $_SESSION['intermediario'] ?? null;

$fechaInicio = $_POST['fechaInicio'] ?? '';
$fechaFin = $_POST['fechaFin'] ?? '';

if (!empty($fechaInicio) && !empty($fechaFin) && $id !== null && $intermediario !== null) {

    try {
        if ($intermediario != 3) {
            if ($intermediario == 89) {
                $query = "SELECT * FROM cotizaciones WHERE cot_fch_cotizacion BETWEEN ? AND ? AND id_usuario = ?";
                $stmt = $enlace->prepare($query);
                $fechaFin .= ":23:59:59";
                $stmt->bind_param("ssi", $fechaInicio, $fechaFin, $id);
            } else {
                $query = "SELECT * FROM cotizaciones c INNER JOIN usuarios us ON us.id_usuario = c.id_usuario WHERE cot_fch_cotizacion BETWEEN ? AND ? AND us.id_Intermediario = ?";
                $stmt = $enlace->prepare($query);
                $fechaFin .= ":23:59:59";
                $stmt->bind_param("ssi", $fechaInicio, $fechaFin, $intermediario);
            }
        } else {
            $query = "SELECT * FROM cotizaciones WHERE cot_fch_cotizacion BETWEEN ? AND ? AND id_usuario = ?";
            $stmt = $enlace->prepare($query);
            $fechaFin .= ":23:59:59";
            $stmt->bind_param("ssi", $fechaInicio, $fechaFin, $id);
        }

        if (!$stmt) {
            echo 0;
            exit;
        }

        if (!$stmt->execute()) {
            echo 0;
            exit;
        }

        $stmt->store_result();
        echo $stmt->num_rows;

        $stmt->close();
        $enlace->close();
    } catch (Exception $e) {
        echo 0; // o "Error"
    }
} else {
    echo 0;
}