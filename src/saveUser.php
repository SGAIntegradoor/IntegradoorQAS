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

    // Saltar si $datos no es un array (campos auxiliares como analista_comercial_nuevo)
    if (!is_array($datos)) continue;

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
            // Verificar si el usuario existe
            $checkUser = mysqli_query($enlace, "SELECT id_usuario FROM usuarios WHERE id_usuario = $id");
            if ($checkUser && mysqli_num_rows($checkUser) > 0) {
                // El usuario existe, simplemente no hubo cambios (valores iguales)
                $respuestas[] = array(
                    "seccion" => $seccion,
                    "ok" => true,
                    "info" => "Sin cambios en los datos"
                );
            } else {
                // El usuario no existe
                $respuestas[] = array(
                    "seccion" => $seccion,
                    "ok" => false,
                    "error" => "No se encontró usuario con id $id para actualizar."
                );
            }
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

/**
 * ==================================================
 * SINCRONIZAR ANALISTAS_FREELANCES
 * Si se actualizó infoCanal.analista_comercial o nombre del usuario,
 * sincronizar con tabla analistas_freelances
 * ==================================================
 */

// Detectar si hay cambios que requieren sincronización con analistas_freelances
$hayActualizacionAnalista = isset($cambios["infoCanal"]["analista_comercial"]);
$hayActualizacionNombre = isset($cambios["usu_nombre"]) || 
                          (isset($cambios["infoUsuario"]["usu_nombres"]) || isset($cambios["infoUsuario"]["usu_apellidos"]));

if (($hayActualizacionAnalista || $hayActualizacionNombre) && !empty($id)) {
    
    // Obtener el documento del usuario
    $usu_documento = null;
    
    // Si viene en cambios directos
    if (isset($cambios["usu_documento"])) {
        $usu_documento = mysqli_real_escape_string($enlace, $cambios["usu_documento"]);
    } else {
        // Si no, consultar de la base de datos
        $queryDoc = "SELECT usu_documento FROM usuarios WHERE id_usuario = $id";
        $resDoc = mysqli_query($enlace, $queryDoc);
        if ($resDoc && mysqli_num_rows($resDoc) > 0) {
            $rowDoc = mysqli_fetch_assoc($resDoc);
            $usu_documento = $rowDoc["usu_documento"];
        }
    }
    
    if (!empty($usu_documento)) {
        // Obtener nombre del freelance
        $nombre_completo_freelance = "";
        if (isset($cambios["usu_nombre"])) {
            $nombre_completo_freelance = mysqli_real_escape_string($enlace, $cambios["usu_nombre"]);
        } elseif (isset($cambios["infoUsuario"]["usu_nombres"]) || isset($cambios["infoUsuario"]["usu_apellidos"])) {
            // Construir desde los campos individuales, consultando lo que no venga
            $queryNombres = "SELECT usu_nombres, usu_apellidos FROM usuarios WHERE id_usuario = $id";
            $resNombres = mysqli_query($enlace, $queryNombres);
            $nombres_db = "";
            $apellidos_db = "";
            if ($resNombres && mysqli_num_rows($resNombres) > 0) {
                $rowNombres = mysqli_fetch_assoc($resNombres);
                $nombres_db = $rowNombres["usu_nombres"];
                $apellidos_db = $rowNombres["usu_apellidos"];
            }
            $nombres_final = isset($cambios["infoUsuario"]["usu_nombres"]) ? $cambios["infoUsuario"]["usu_nombres"] : $nombres_db;
            $apellidos_final = isset($cambios["infoUsuario"]["usu_apellidos"]) ? $cambios["infoUsuario"]["usu_apellidos"] : $apellidos_db;
            $nombre_completo_freelance = mysqli_real_escape_string($enlace, trim($nombres_final . ' ' . $apellidos_final));
        }
        
        // Datos del analista (si viene)
        $id_analista = isset($cambios["infoCanal"]["analista_comercial"]) 
            ? mysqli_real_escape_string($enlace, $cambios["infoCanal"]["analista_comercial"]) 
            : null;
        $nombre_analista = isset($cambios["analista_comercial_nuevo"]) 
            ? mysqli_real_escape_string($enlace, $cambios["analista_comercial_nuevo"]) 
            : null;
        
        // Verificar si existe registro en analistas_freelances
        $queryCheck = "SELECT id, nombre_completo_freelance FROM analistas_freelances WHERE id_usuario = '$usu_documento'";
        $resCheck = mysqli_query($enlace, $queryCheck);
        
        if ($resCheck && mysqli_num_rows($resCheck) > 0) {
            $rowFreelance = mysqli_fetch_assoc($resCheck);
            $nombreActualEnTabla = trim($rowFreelance["nombre_completo_freelance"] ?? "");
            
            // Existe: actualizar
            $setCampos = array();
            
            // Si el nombre en la tabla está vacío o es diferente al nuevo, actualizar
            if (!empty($nombre_completo_freelance) && $nombre_completo_freelance !== $nombreActualEnTabla) {
                $setCampos[] = "nombre_completo_freelance = '$nombre_completo_freelance'";
            } elseif (empty($nombreActualEnTabla)) {
                // Si el nombre en la tabla está vacío, obtener el nombre actual del usuario
                $queryNombreUsuario = "SELECT CONCAT(usu_nombre, ' ', usu_apellido) as nombre_completo FROM usuarios WHERE id_usuario = $id";
                $resNombreUsuario = mysqli_query($enlace, $queryNombreUsuario);
                if ($resNombreUsuario && mysqli_num_rows($resNombreUsuario) > 0) {
                    $rowNombreUsuario = mysqli_fetch_assoc($resNombreUsuario);
                    $nombreDesdeUsuarios = mysqli_real_escape_string($enlace, trim($rowNombreUsuario["nombre_completo"]));
                    if (!empty($nombreDesdeUsuarios)) {
                        $setCampos[] = "nombre_completo_freelance = '$nombreDesdeUsuarios'";
                    }
                }
            }
            
            if ($nombre_analista !== null) {
                $setCampos[] = "nombre_analista = '$nombre_analista'";
            }
            if ($id_analista !== null) {
                $setCampos[] = "id_analista = '$id_analista'";
            }
            
            if (!empty($setCampos)) {
                $queryUpdateFreelance = "
                    UPDATE analistas_freelances 
                    SET " . implode(", ", $setCampos) . "
                    WHERE id_usuario = '$usu_documento'
                ";
                
                if (mysqli_query($enlace, $queryUpdateFreelance)) {
                    $respuestas[] = array(
                        "seccion" => "analistas_freelances",
                        "ok" => true,
                        "accion" => "update"
                    );
                } else {
                    $respuestas[] = array(
                        "seccion" => "analistas_freelances",
                        "ok" => false,
                        "accion" => "update",
                        "error" => mysqli_error($enlace)
                    );
                }
            }
        } else {
            // No existe: insertar nuevo registro (solo si hay datos de analista)
            if ($hayActualizacionAnalista) {
                // Obtener datos adicionales del usuario para el insert si no tenemos el nombre
                if (empty($nombre_completo_freelance)) {
                    $queryUserData = "SELECT CONCAT(usu_nombres, ' ', usu_apellidos) as nombre_completo, usu_email 
                                      FROM usuarios WHERE id_usuario = $id";
                    $resUserData = mysqli_query($enlace, $queryUserData);
                    
                    if ($resUserData && mysqli_num_rows($resUserData) > 0) {
                        $rowUser = mysqli_fetch_assoc($resUserData);
                        $nombre_completo_freelance = mysqli_real_escape_string($enlace, $rowUser["nombre_completo"]);
                        $correo = mysqli_real_escape_string($enlace, $rowUser["usu_email"]);
                    }
                } else {
                    // Obtener solo el correo
                    $queryEmail = "SELECT usu_email FROM usuarios WHERE id_usuario = $id";
                    $resEmail = mysqli_query($enlace, $queryEmail);
                    $correo = "";
                    if ($resEmail && mysqli_num_rows($resEmail) > 0) {
                        $rowEmail = mysqli_fetch_assoc($resEmail);
                        $correo = mysqli_real_escape_string($enlace, $rowEmail["usu_email"]);
                    }
                }
                
                $queryInsertFreelance = "
                    INSERT INTO analistas_freelances 
                    (id_usuario, nombre_completo_freelance, correo, nombre_analista, id_analista)
                    VALUES ('$usu_documento', '$nombre_completo_freelance', '$correo', '$nombre_analista', '$id_analista')
                ";
                
                if (mysqli_query($enlace, $queryInsertFreelance)) {
                    $respuestas[] = array(
                        "seccion" => "analistas_freelances",
                        "ok" => true,
                        "accion" => "insert"
                    );
                } else {
                    $respuestas[] = array(
                        "seccion" => "analistas_freelances",
                        "ok" => false,
                        "accion" => "insert",
                        "error" => mysqli_error($enlace)
                    );
                }
            }
        }
    }
}

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
