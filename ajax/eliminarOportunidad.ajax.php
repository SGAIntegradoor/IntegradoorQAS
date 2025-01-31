<?php

require_once("../controladores/oportunidades.controlador.php");
// require_once "../modelos/oportunidades.modelo.php";

function eliminarOportunidad($id_oportunidad, $id_oferta){
    $respuesta = ControladorOportunidades::ctrEliminarOportunidad($id_oportunidad, $id_oferta);
    echo json_encode($respuesta);
}

if(isset($_POST["id_oportunidad"]) && isset($_POST["id_oferta"])){
    eliminarOportunidad($_POST["id_oportunidad"], $_POST["id_oferta"]);
}


?>