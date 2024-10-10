<?php
// checkSession.php

session_start();

// Verifica si la sesión está activa
if (isset($_SESSION['iniciarSesion'])) {
    echo json_encode(['sessionActive' => true]);
} else {
    echo json_encode(['sessionActive' => false]);
}
?>