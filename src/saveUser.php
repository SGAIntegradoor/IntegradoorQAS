<?php
// saveUser.php - guardar/actualizar usuario y secciones relacionadas

require_once "../config/dbconfig.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

$input   = json_decode(file_get_contents("php://input"), true);
$id      = isset($input["id"]) ? $input["id"] : null;
$cambios = isset($input["cambios"]) ? $input["cambios"] : [];

/**
 * ==================================================
 * VALIDACIÓN DE SESIÓN / ROL
 * ==================================================
 */
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], array(1, 10, 11, 12, 22, 23))) {
    echo json_encode(array(
        "success" => false,
        "mensaje" => "No autorizado"
    ));
    exit;
}

mysqli_set_charset($enlace, "utf8");

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
 * CREAR USUARIO (SIN ID)
 * ==================================================
 */
if (empty($id)) {

    if (!isset($cambios["infoUsuario"]) || empty($cambios["infoUsuario"])) {
        echo json_encode(array(
            "success" => false,
            "mensaje" => "Falta información de usuario para crear."
        ));
        exit;
    }

    $datosUsuario = array();

    foreach ($cambios["infoUsuario"] as $campo => $valor) {

        if ($campo === "usu_password") {
            $valor = crypt($valor, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
        }

        if (is_array($valor)) {
            $valor = json_encode($valor, JSON_UNESCAPED_UNICODE);
        }

        $datosUsuario[$campo] = mysqli_real_escape_string($enlace, $valor);
    }

    $campos  = array_keys($datosUsuario);
    $valores = array();

    foreach ($datosUsuario as $v) {
        $valores[] = "'" . $v . "'";
    }

    $insertUsuario = "
        INSERT INTO usuarios (" . implode(", ", $campos) . ")
        VALUES (" . implode(", ", $valores) . ")
    ";

    if (!mysqli_query($enlace, $insertUsuario)) {
        echo json_encode(array(
            "success" => false,
            "mensaje" => "Error al crear usuario",
            "error"   => mysqli_error($enlace)
        ));
        exit;
    }

    $id = mysqli_insert_id($enlace);

    $respuestas[] = array(
        "seccion" => "infoUsuario",
        "ok" => true,
        "accion" => "crearUsuario"
    );
}

// Normalización del ID
$id = intval($id);

/**
 * ==================================================
 * ACTUALIZAR SECCIONES
 * ==================================================
 */
foreach ($cambios as $seccion => $datos) {

    if (empty($datos)) continue;

    // Evitar doble UPDATE al crear
    if (
        $seccion === "infoUsuario" &&
        empty($input["id"]) &&
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
            $table = "usuarios";
            $query = "UPDATE usuarios SET " . implode(", ", $set) . " WHERE id_usuario = $id";
            break;

        case "infoFinanciera":
            $table = "informacion_financiera_user";
            $idField = "id_info_entidad_fin";
            $query = "UPDATE informacion_financiera_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
            break;

        case "infoCanal":
            $table = "informacion_canal_user";
            $idField = "id_info_canal";
            $query = "UPDATE informacion_canal_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
            break;

        case "infoAseguradoras":
            $table = "claves_aseguradoras_user";
            $idField = "id_aseguradoras_user";
            $query = "UPDATE claves_aseguradoras_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
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

    if (!$res) {
        $respuestas[] = array(
            "seccion" => $seccion,
            "ok" => false,
            "error" => mysqli_error($enlace)
        );
        continue;
    }

    $afectadas = mysqli_affected_rows($enlace);

    // Usuarios: aunque no afecte filas, está OK
    if ($table === "usuarios") {
        $respuestas[] = array(
            "seccion" => $seccion,
            "ok" => true,
            "afectadas" => $afectadas
        );
        continue;
    }

    // Tablas secundarias: si no existe, INSERT
    if ($afectadas === 0) {

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
                "error" => mysqli_error($enlace)
            );
        }

        continue;
    }

    // UPDATE exitoso
    $respuestas[] = array(
        "seccion" => $seccion,
        "ok" => true,
        "afectadas" => $afectadas
    );
}

/**
 * ==================================================
 * RESPUESTA FINAL
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
