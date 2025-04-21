<?php

require_once "../config/dbconfig.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents("php://input"), true);
$id = $input["id"] ?? null;
$cambios = $input["cambios"] ?? [];

if ($id && in_array($_SESSION["rol"], [1, 10, 11, 12, 22, 23])) {
    mysqli_set_charset($enlace, "utf8");

    if (!$id || !$cambios) {
        echo json_encode(["success" => false, "mensaje" => "Datos incompletos"]);
        exit;
    }

    $respuestas = [];

    foreach ($cambios as $seccion => $datos) {
        if (empty($datos)) continue;

        $set = [];
        foreach ($datos as $campo => $valor) {
            $valor = mysqli_real_escape_string($enlace, $valor);
            $set[] = "$campo = '$valor'";
        }

        if (empty($set)) continue;

        switch ($seccion) {
            case "infoUsuario":
                $query = "UPDATE usuarios SET " . implode(", ", $set) . " WHERE id_usuario = $id";
                break;

            case "infoFinanciera":
                $query = "UPDATE informacion_financiera_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
                break;

            case "infoCanal":
                $query = "UPDATE informacion_canal_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
                break;

            case "infoAseguradoras":
                $query = "UPDATE claves_aseguradoras_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
                break;

            default:
                $respuestas[] = [
                    "seccion" => $seccion,
                    "ok" => false,
                    "error" => "Sección no reconocida"
                ];
                continue 2; // salta al siguiente foreach
        }

        $res = mysqli_query($enlace, $query);

        if ($res) {
            $respuestas[] = ["seccion" => $seccion, "ok" => true];
        } else {
            $respuestas[] = [
                "seccion" => $seccion,
                "ok" => false,
                "error" => mysqli_error($enlace)
            ];
        }
    }

    echo json_encode([
        "success" => true,
        "resultado" => $respuestas
    ]);
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "No autorizado o ID inválido"
    ]);
}
