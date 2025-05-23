<?php
session_start();
require_once "../config/db.php"; // Archivo de configuración con la conexión a la base de datos
require_once "../config/conexion.php"; // Archivo de configuración con la conexión a la base de datos

if ((isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK)) {
    $idUsuario = $_POST['idUsuario'];
    $idDocUser = $_POST['documento'];
    $tipInput = $_POST['inputId'];
    $directorio = ($tipInput == "imgUser") 
        ? '../vistas/img/usuarios/' . $idDocUser . '/imgUser' 
        : '../vistas/img/usuarios/' . $idDocUser . '/logoPDF'; // Carpeta donde se guardarán las imágenes
    $archivoTemporal = $_FILES['file']['tmp_name'];
    $nombreArchivo = basename($_FILES['file']['name']);
    $rutaCompleta = $directorio . "/" . $nombreArchivo;

    // Validar el tamaño del archivo (máximo 2MB)
    if ($_FILES['file']['size'] > 2097152) {
        echo json_encode(["warning" => "El archivo es demasiado grande. Máximo 2MB"]);
        exit;
    }

    // Validar el tipo de archivo (solo imágenes)
    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['file']['type'], $tiposPermitidos)) {
        echo json_encode(["warning" => "Tipo de archivo no permitido. Solo JPG, PNG o GIF"]);
        exit;
    }

    // Crear el directorio si no existe
    if (!is_dir($directorio) && !mkdir($directorio, 0755, true)) {
        echo json_encode(["error" => "No se pudo crear el directorio para la carga de archivos"]);
        exit;
    }

    // Mover el archivo al servidor
    if (move_uploaded_file($archivoTemporal, $rutaCompleta)) {
        // Actualizar la base de datos con la nueva ruta de la imagen
        $tableUserSelect = ($tipInput == "imgUser") ? "usu_foto" : "usu_logo_pdf";
        $sql = "UPDATE usuarios SET $tableUserSelect = ? WHERE id_usuario = ?";
        $stmt = $con->prepare($sql);

        if (!$stmt) {
            echo json_encode(["error" => "Error en la consulta SQL: " . $con->error]);
            exit;
        }

        $urlSinPuntos = (strpos($rutaCompleta, '../') === 0) ? substr($rutaCompleta, 3) : $rutaCompleta; // Eliminar los puntos iniciales
        $stmt->bind_param("si", $urlSinPuntos, $idUsuario);

        if ($stmt->execute()) {
            // Actualizar la sesión con la nueva ruta
            if ($tipInput == "imgLogo") {
                $_SESSION['imgPDF'] = $urlSinPuntos; // Actualizar la variable de sesión para el logo
            } elseif ($tipInput == "imgUser") {
                $_SESSION['foto'] = $urlSinPuntos; // Actualizar para la foto de perfil
            }

            echo json_encode(["success" => "Imagen subida y guardada con éxito", "ruta" => $urlSinPuntos]);
        } else {
            echo json_encode(["error" => "Error al ejecutar la consulta: " . $stmt->error]);
        }
    } else {
        echo json_encode(["error" => "Error al mover el archivo"]);
    }
} else {
    echo json_encode(["error" => "No se ha subido ningún archivo"]);
}
