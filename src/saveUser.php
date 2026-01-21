<?php
// saveUser.php - guardar/actualizar usuario y secciones relacionadas

require_once "../config/dbconfig.php";
session_start();

// Mostrar errores (solo desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Responder siempre JSON
header('Content-Type: application/json; charset=utf-8');

$input   = json_decode(file_get_contents("php://input"), true);
$id      = isset($input["id"]) ? $input["id"] : null;
$cambios = isset($input["cambios"]) ? $input["cambios"] : [];

// Validación de sesión / rol
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], array(1, 10, 11, 12, 22, 23))) {
    echo json_encode(array(
        "success" => false,
        "mensaje" => "No autorizado"
    ));
    exit;
}

// Charset
mysqli_set_charset($enlace, "utf8");

// Validación básica
if (empty($cambios)) {
    echo json_encode(array(
        "success" => false,
        "mensaje" => "Datos incompletos"
    ));
    exit;
}

$respuestas = array();

/**
 * ==================================================
 * CREAR USUARIO (cuando no hay ID)
 * ==================================================
 */
if (empty($id)) {

    if (isset($cambios["infoUsuario"]) && !empty($cambios["infoUsuario"])) {

        $datosUsuario = array();

        foreach ($cambios["infoUsuario"] as $campo => $valor) {

            if ($campo === "usu_password") {
                $valor = crypt($valor, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
            }

            // 🔧 FIX ARRAY (PHP 7.3)
            if (is_array($valor)) {
                $valor = json_encode($valor, JSON_UNESCAPED_UNICODE);
            }

            $valor = mysqli_real_escape_string($enlace, $valor);
            $datosUsuario[$campo] = $valor;
        }

        $campos  = array_keys($datosUsuario);
        $valores = array_values($datosUsuario);

        $valoresEscapados = array();
        foreach ($valores as $v) {
            $valoresEscapados[] = "'" . mysqli_real_escape_string($enlace, $v) . "'";
        }

        $insertUsuario = "
            INSERT INTO usuarios (" . implode(", ", $campos) . ")
            VALUES (" . implode(", ", $valoresEscapados) . ")
        ";

        if (mysqli_query($enlace, $insertUsuario)) {
            $id = mysqli_insert_id($enlace);
            $respuestas[] = array(
                "seccion" => "infoUsuario",
                "ok" => true,
                "accion" => "crearUsuario"
            );
        } else {
            echo json_encode(array(
                "success" => false,
                "mensaje" => "Error al crear usuario: " . mysqli_error($enlace)
            ));
            exit;
        }
    } else {
        echo json_encode(array(
            "success" => false,
            "mensaje" => "Falta información de usuario para crear."
        ));
        exit;
    }
}

// Normalizar ID
$id = intval($id);

/**
 * ==================================================
 * ACTUALIZAR SECCIONES
 * ==================================================
 */
foreach ($cambios as $seccion => $datos) {

    if (empty($datos)) continue;

    // Evitar reprocesar infoUsuario recién creada
    if (
        $seccion === "infoUsuario" &&
        empty($input["id"]) &&
        isset($respuestas[0]) &&
        isset($respuestas[0]["accion"]) &&
        $respuestas[0]["accion"] === "crearUsuario"
    ) {
        continue;
    }

    $set = array();

    foreach ($datos as $campo => $valor) {

        if ($seccion === "infoUsuario" && $campo === "usu_password") {
            $valor = crypt($valor, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
        }

        // 🔧 FIX ARRAY (PHP 7.3)
        if (is_array($valor)) {
            $valor = json_encode($valor, JSON_UNESCAPED_UNICODE);
        }

        $valor = mysqli_real_escape_string($enlace, $valor);
        $set[] = $campo . " = '" . $valor . "'";
        $datos[$campo] = $valor;
    }

    if (empty($set)) continue;

    $table   = null;
    $idField = null;
    $query   = "";

    switch ($seccion) {
        case "infoUsuario":
            $query = "UPDATE usuarios SET " . implode(", ", $set) . " WHERE id_usuario = $id";
            $table = "usuarios";
            break;

        case "infoFinanciera":
            $query = "UPDATE informacion_financiera_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
            $table = "informacion_financiera_user";
            $idField = "id_info_entidad_fin";
            break;

        case "infoCanal":
            $query = "UPDATE informacion_canal_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
            $table = "informacion_canal_user";
            $idField = "id_info_canal";
            break;

        case "infoAseguradoras":
            $query = "UPDATE claves_aseguradoras_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
            $table = "claves_aseguradoras_user";
            $idField = "id_aseguradoras_user";
            break;

        default:
            $respuestas[] = array(
                "seccion" => $seccion,
                "ok" => false,
                "error" => "Sección no reconocida"
            );
            continue 2;
    }

    $res = mysqli_query($enlace, $query);
    $afectadas = mysqli_affected_rows($enlace);

    if ($res && $afectadas > 0) {
        $respuestas[] = array("seccion" => $seccion, "ok" => true);
        continue;
    }

    if ($res && $afectadas === 0) {

        if ($table === "usuarios") {
            $respuestas[] = array(
                "seccion" => $seccion,
                "ok" => false,
                "error" => "No se encontró usuario con id $id para actualizar."
            );
            continue;
        }

        if (empty($idField)) {
            $respuestas[] = array(
                "seccion" => $seccion,
                "ok" => false,
                "error" => "Falta idField para insertar"
            );
            continue;
        }

        $campos  = array_keys($datos);
        $valores = array_values($datos);

        $campos[]  = "id_usuario";
        $valores[] = $id;

        $valoresEscapados = array();
        foreach ($valores as $v) {
            $valoresEscapados[] = "'" . mysqli_real_escape_string($enlace, $v) . "'";
        }

        $insertQuery = "
            INSERT INTO $table ($idField, " . implode(", ", $campos) . ")
            VALUES (NULL, " . implode(", ", $valoresEscapados) . ")
        ";

        if (mysqli_query($enlace, $insertQuery)) {
            $respuestas[] = array(
                "seccion" => $seccion,
                "ok" => true,
                "accion" => "insert"
            );
        } else {
            $respuestas[] = array(
                "seccion" => $seccion,
                "ok" => false,
                "accion" => "insert",
                "error" => mysqli_error($enlace)
            );
        }
    }
}

/**
 * ==================================================
 * RESPUESTA FINAL (PHP 7.3)
 * ==================================================
 */
$hayErrores = false;
foreach ($respuestas as $r) {
    if ($r["ok"] === false) {
        $hayErrores = true;
        break;
    }
}

echo json_encode(array(
    "success" => !$hayErrores,
    "resultado" => $respuestas,
    "nuevo_id" => $id
));
