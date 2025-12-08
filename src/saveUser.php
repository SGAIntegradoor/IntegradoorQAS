
<?php
// saveUser.php - guardar/actualizar usuario y secciones relacionadas

require_once "../config/dbconfig.php";
session_start();

// Mostrar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Responder siempre JSON
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents("php://input"), true);
$id = $input["id"] ?? null;
$cambios = $input["cambios"] ?? [];

// Validación básica de sesión/rol
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], [1, 10, 11, 12, 22, 23])) {
    echo json_encode([
        "success" => false,
        "mensaje" => "No autorizado"
    ]);
    exit;
}

// Forzar charset
mysqli_set_charset($enlace, "utf8");

// Validación de entrada
if (empty($cambios)) {
    echo json_encode(["success" => false, "mensaje" => "Datos incompletos"]);
    exit;
}

$respuestas = [];

// Si no hay ID: crear nuevo usuario (solo si vienen datos en infoUsuario)
if (empty($id)) {
    if (isset($cambios["infoUsuario"]) && !empty($cambios["infoUsuario"])) {
        $datosUsuario = [];
        foreach ($cambios["infoUsuario"] as $campo => $valor) {
            if ($campo == "usu_password") {
                // conserva tu método de encriptación
                $valor = crypt($valor, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
            }
            // sanitizar
            $valor = mysqli_real_escape_string($enlace, $valor);
            $datosUsuario[$campo] = $valor;
        }

        $campos = array_keys($datosUsuario);
        $valores = array_values($datosUsuario);

        $valoresEscapados = array_map(function($v) use ($enlace) {
            return "'" . mysqli_real_escape_string($enlace, $v) . "'";
        }, $valores);

        $insertUsuario = "INSERT INTO usuarios (" . implode(", ", $campos) . ") VALUES (" . implode(", ", $valoresEscapados) . ")";
        $resInsert = mysqli_query($enlace, $insertUsuario);

        if ($resInsert) {
            $id = mysqli_insert_id($enlace); // nuevo id asignado
            $respuestas[] = ["seccion" => "infoUsuario", "ok" => true, "accion" => "crearUsuario"];
        } else {
            echo json_encode(["success" => false, "mensaje" => "Error al crear usuario: " . mysqli_error($enlace)]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "mensaje" => "Falta información de usuario para crear."]);
        exit;
    }
}

// Normalizar id a entero para evitar inyección al usar directamente en SQL
$id = intval($id);

// Procesar las demás secciones (o la misma infoUsuario si se envió con id)
foreach ($cambios as $seccion => $datos) {
    if (empty($datos)) continue;

    // Si ya creamos usuario cuando no había id, evitamos procesar infoUsuario de nuevo
    if ($seccion === "infoUsuario" && !empty($input["id"]) === false && isset($respuestas[0]) && $respuestas[0]["accion"] === "crearUsuario") {
        // ya procesado en la creación
        continue;
    }

    $set = [];
    // Sanitizar y construir el SET para UPDATE
    foreach ($datos as $campo => $valor) {
        // Si es password y quieres aplicar criptado al actualizar:
        if ($seccion === "infoUsuario" && $campo === "usu_password") {
            $valor = crypt($valor, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
        }
        $valor = mysqli_real_escape_string($enlace, $valor);
        $set[] = "$campo = '$valor'";
        // sobrescribo en $datos para usar en posibles inserts
        $datos[$campo] = $valor;
    }

    if (empty($set)) continue;

    // Inicializar variables locales
    $table = null;
    $idField = null;
    $query = "";

    switch ($seccion) {
        case "infoUsuario":
            // UPDATE directo a usuarios (si no existe, devolvemos error; no intentamos INSERT genérico aquí)
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
            $respuestas[] = [
                "seccion" => $seccion,
                "ok" => false,
                "error" => "Sección no reconocida"
            ];
            continue 2; // pasa a la siguiente sección
    }

    // Ejecutar UPDATE
    $res = mysqli_query($enlace, $query);
    $afectadas = mysqli_affected_rows($enlace);

    if ($res && $afectadas > 0) {
        // UPDATE exitoso con filas afectadas
        $respuestas[] = ["seccion" => $seccion, "ok" => true];
        continue;
    }

    // Si la ejecución del UPDATE fue exitosa pero no hubo filas afectadas
    if ($res && $afectadas === 0) {
        // Si la tabla es 'usuarios' asumimos que el usuario no existe para actualizar
        if ($table === "usuarios") {
            $respuestas[] = [
                "seccion" => $seccion,
                "ok" => false,
                "error" => "No se encontró usuario con id $id para actualizar."
            ];
            continue;
        }

        // Para tablas auxiliares intentamos INSERT si no existe registro
        if (empty($idField)) {
            // protección extra: no construir INSERT sin idField definido
            $respuestas[] = [
                "seccion" => $seccion,
                "ok" => false,
                "error" => "Falta idField para realizar insert en la tabla $table"
            ];
            continue;
        }

        $campos = array_keys($datos);
        $valores = array_values($datos);

        // Agregar id_usuario al insert
        $campos[] = "id_usuario";
        $valores[] = $id;

        $valoresEscapados = array_map(function($v) use ($enlace) {
            return "'" . mysqli_real_escape_string($enlace, $v) . "'";
        }, $valores);

        // Construir INSERT con la columna autoincrement como NULL
        $insertQuery = "INSERT INTO $table ($idField, " . implode(", ", $campos) . ") VALUES (NULL, " . implode(", ", $valoresEscapados) . ")";
        $insertRes = mysqli_query($enlace, $insertQuery);

        if ($insertRes) {
            $respuestas[] = ["seccion" => $seccion, "ok" => true, "accion" => "insert"];
        } else {
            $respuestas[] = [
                "seccion" => $seccion,
                "ok" => false,
                "error" => mysqli_error($enlace),
                "accion" => "insert"
            ];
        }

        continue;
    }

    // Si hubo error ejecutando el UPDATE (res === false)
    if ($res === false) {
        $respuestas[] = [
            "seccion" => $seccion,
            "ok" => false,
            "error" => mysqli_error($enlace)
        ];
    }
}

// Respuesta final
echo json_encode([
    "success" => true,
    "resultado" => $respuestas,
    "nuevo_id" => $id
]);
