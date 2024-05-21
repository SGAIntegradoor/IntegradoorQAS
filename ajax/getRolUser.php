<?php
session_start();
if(isset($_SESSION['rol'])){
    echo json_encode(array('id'=> $_SESSION['idUsuario'],'rol'=> $_SESSION['rol']));
} else {
    echo json_encode(['error' => 'No se encontro ningun rol en session']);
}