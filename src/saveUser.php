<?php

// require_once "../config/dbconfig.php";
// session_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// $input = json_decode(file_get_contents("php://input"), true);
// $id = $input["id"] ?? null;
// $cambios = $input["cambios"] ?? [];

// if ($id && in_array($_SESSION["rol"], [1, 10, 11, 12, 22, 23])) {
//     mysqli_set_charset($enlace, "utf8");

//     if (!$id || !$cambios) {
//         echo json_encode(["success" => false, "mensaje" => "Datos incompletos"]);
//         exit;
//     }

//     $respuestas = [];

//     foreach ($cambios as $seccion => $datos) {
//         if (empty($datos)) continue;

//         $set = [];
//         foreach ($datos as $campo => $valor) {
//             $valor = mysqli_real_escape_string($enlace, $valor);
//             $set[] = "$campo = '$valor'";
//             $datos[$campo] = $valor; // limpio los valores también para el insert
//         }

//         if (empty($set)) continue;

//         switch ($seccion) {
//             case "infoUsuario":
//                 $query = "UPDATE usuarios SET " . implode(", ", $set) . " WHERE id_usuario = $id";
//                 $table = "usuarios"; // No insert aquí
//                 break;

//             case "infoFinanciera":
//                 $query = "UPDATE informacion_financiera_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
//                 $table = "informacion_financiera_user";
//                 $idField = "id_info_entidad_fin";
//                 break;

//             case "infoCanal":
//                 $query = "UPDATE informacion_canal_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
//                 $table = "informacion_canal_user";
//                 $idField = "id_info_canal";
//                 break;

//             case "infoAseguradoras":
//                 $query = "UPDATE claves_aseguradoras_user SET " . implode(", ", $set) . " WHERE id_usuario = $id";
//                 $table = "claves_aseguradoras_user";
//                 $idField = "id_aseguradoras_user";
//                 break;

//             default:
//                 $respuestas[] = [
//                     "seccion" => $seccion,
//                     "ok" => false,
//                     "error" => "Sección no reconocida"
//                 ];
//                 continue 2;
//         }

//         $res = mysqli_query($enlace, $query);
//         $afectadas = mysqli_affected_rows($enlace);

//         if ($res && $afectadas > 0) {
//             $respuestas[] = ["seccion" => $seccion, "ok" => true];
//         } elseif ($res && $afectadas === 0 && $table) {
//             // Hacer INSERT si no existe
//             $campos = array_keys($datos);
//             $valores = array_values($datos);

//             // Agregar id_usuario
//             $campos[] = "id_usuario";
//             $valores[] = $id;

//             // Escapar comillas en los valores
//             $valoresEscapados = array_map(function($v) use ($enlace) {
//                 return "'" . mysqli_real_escape_string($enlace, $v) . "'";
//             }, $valores);

//             $insertQuery = "INSERT INTO $table ($idField, " . implode(", ", $campos) . ") VALUES (NULL, " . implode(", ", $valoresEscapados) . ")";
//             $insertRes = mysqli_query($enlace, $insertQuery);

//             // echo "INSERT INTO $table ($idField, " . implode(", ", $campos) . ") VALUES (NULL, " . implode(", ", $valoresEscapados) . ")";
//             // die();
//             if ($insertRes) {
//                 $respuestas[] = ["seccion" => $seccion, "ok" => true, "accion" => "insert"];
//             } else {
//                 $respuestas[] = [
//                     "seccion" => $seccion,
//                     "ok" => false,
//                     "error" => mysqli_error($enlace),
//                     "accion" => "insert"
//                 ];
//             }
//         } else {
//             $respuestas[] = [
//                 "seccion" => $seccion,
//                 "ok" => false,
//                 "error" => mysqli_error($enlace)
//             ];
//         }
//     }

//     echo json_encode([
//         "success" => true,
//         "resultado" => $respuestas
//     ]);
// } else {
//     echo json_encode([
//         "success" => false,
//         "mensaje" => "No autorizado o ID inválido"
//     ]);
// }

require_once "../config/dbconfig.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents("php://input"), true);
$id = $input["id"] ?? null;
$cambios = $input["cambios"] ?? [];

if (in_array($_SESSION["rol"], [1, 10, 11, 12, 22, 23])) {
    mysqli_set_charset($enlace, "utf8");

    if (!$cambios) {
        echo json_encode(["success" => false, "mensaje" => "Datos incompletos"]);
        exit;
    }

    $respuestas = [];

    // Si no hay ID, creamos nuevo usuario
    if (!$id) {
        if (isset($cambios["infoUsuario"]) && !empty($cambios["infoUsuario"])) {
            $datosUsuario = [];
            foreach ($cambios["infoUsuario"] as $campo => $valor) {
                if($campo == "usu_password"){
                    $valor = crypt($valor, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                }
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
                $id = mysqli_insert_id($enlace); // obtenemos el nuevo ID
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

    // Ahora procedemos igual que antes para las demás secciones
    foreach ($cambios as $seccion => $datos) {
        if (empty($datos)) continue;

        // Ya no procesamos infoUsuario otra vez si ya lo hicimos
        if ($seccion == "infoUsuario" && !$input["id"]) {
            continue;
        }

        $set = [];
        foreach ($datos as $campo => $valor) {
            $valor = mysqli_real_escape_string($enlace, $valor);
            $set[] = "$campo = '$valor'";
            $datos[$campo] = $valor; // limpio los valores también para el insert
        }

        if (empty($set)) continue;

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
                $respuestas[] = [
                    "seccion" => $seccion,
                    "ok" => false,
                    "error" => "Sección no reconocida"
                ];
                continue 2;
        }

        $res = mysqli_query($enlace, $query);
        $afectadas = mysqli_affected_rows($enlace);

        if ($res && $afectadas > 0) {
            $respuestas[] = ["seccion" => $seccion, "ok" => true];
        } elseif ($res && $afectadas === 0 && isset($table)) {
            // Hacer INSERT si no existe
            $campos = array_keys($datos);
            $valores = array_values($datos);

            // Agregar id_usuario
            $campos[] = "id_usuario";
            $valores[] = $id;

            $valoresEscapados = array_map(function($v) use ($enlace) {
                return "'" . mysqli_real_escape_string($enlace, $v) . "'";
            }, $valores);

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
        "resultado" => $respuestas,
        "nuevo_id" => $id // si fue creado uno nuevo, puedes saberlo
    ]);

} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "No autorizado"
    ]);
}