<?php 

session_start();

/* Conectar a la base de datos */
require_once("../modelos/conexion.php"); // Contiene función que conecta a la base de datos

// time zone america/bogota
date_default_timezone_set('America/Bogota');

// Mostrar errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!empty($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents("php://input"), true);
} else {
    $data = $_POST;
}

try {
    $pdo = Conexion::conectar();
    $pdo->exec("SET NAMES 'utf8mb4'");

    $stmt = $pdo->prepare("INSERT INTO comentarios_usuarios (id_comentario, modulo, id_general, comentario, fecha_comentario, id_user_comentario, nombre_usuario_comentario) VALUES (NULL , :modulo, :id_general, :comentario, NOW(), :id_user_comentario, :nombre_usuario_comentario)");
    //bindParam para evitar inyecciones SQL
    $stmt->bindParam(':modulo', $data['modulo'], PDO::PARAM_STR);
    $stmt->bindParam(':id_general', $data['id_general'], PDO::PARAM_INT);
    $stmt->bindParam(':comentario', $data['comentario'], PDO::PARAM_STR);
    $stmt->bindParam(':id_user_comentario', $_SESSION['idUsuario'], PDO::PARAM_INT);
    $stmt->bindParam(':nombre_usuario_comentario', $data['nombre_usuario_comentario'], PDO::PARAM_STR);

    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Comentario guardado correctamente"], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al guardar el comentario"], JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) {
    
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
} finally {
    // Cerrar conexión a la base de datos
    if (isset($pdo)) {
        $pdo = null;
    }
}