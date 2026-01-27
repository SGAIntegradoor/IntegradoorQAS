<?php 


require_once "../modelos/conexion.php";

$pdo = Conexion::conectar();
$pdo->exec("SET NAMES 'utf8mb4'");

if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $data = json_decode(file_get_contents("php://input"), true);
} else {
    $data = $_POST;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM comentarios_usuarios WHERE id_general = :id_usuario ORDER BY fecha_comentario DESC");
    $stmt->bindParam(':id_usuario', $data['id_general'], PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["status" => "error", "message" => "No se encontraron comentarios", "sqlError" => $stmt->errorInfo()], JSON_UNESCAPED_UNICODE);
    }
} catch (Throwable $e) {
    echo json_encode(["status"=> "error", "message"=> $e->getMessage()], JSON_UNESCAPED_UNICODE);
} finally {
    if (isset($pdo)) {
        $pdo = null;
    }
}


?>