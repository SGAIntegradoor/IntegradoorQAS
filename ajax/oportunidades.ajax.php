    <?php
    session_start();
    require_once '../config/dbconfig.php';
    mysqli_set_charset($enlace, "utf8mb4");
    // Mostrar errores (solo para desarrollo, no en producción)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // echo $_POST["id_user_freelance"];die();  corregir

    if (isset($_POST["manual"])) {
        $idCotizacion = $_POST["idCotizacion"] == "" ? "" : $_POST["idCotizacion"];
        $idCotAseguradora = $_POST["idCotAseguradora"] == "" ? "" : $_POST["idCotAseguradora"];
        $valor_cotizacion = $_POST["valor_cotizacion"] == "" ? "" : $_POST["valor_cotizacion"];
        $idOferta = $_POST["idOferta"];
        $mesOportunidad = $_POST["mesOportunidad"];
        $canalOportunidad = empty($_POST['canalOportunidad']) ? '' : $_POST['canalOportunidad'];
        $razonPerdidaOportunidad = empty($_POST['razonPerdidaOportunidad']) ? '' : $_POST['razonPerdidaOportunidad'];
        $otraRazon = empty($_POST['otraRazon']) ? '' : $_POST['otraRazon'];
        $asesor_freelance = $_POST["asesor_freelance"];
        $id_user_freelance = empty($_POST["id_user_freelance"]) ? 0 : $_POST["id_user_freelance"];
        $ramo = $_POST["ramo"];
        $placa = $_POST["placa"];
        $oneroso = $_POST["oneroso"];
        $aseguradora = $_POST["aseguradora"];
        $analista_comercial = $_POST["analista_comercial"];
        $id_analista_comercial = $_POST["id_analista_comercial"];
        $estado = $_POST["estado"];
        $noPoliza = $_POST["noPoliza"] == "" ? "null" : $_POST["noPoliza"];
        $asegurado = $_POST["asegurado"];
        $id_asegurado = $_POST["id_asegurado"];
        $prima_sin_iva = $_POST["prima_sin_iva"];
        $gastos = $_POST["gastos"];
        $asistencias = $_POST["asistencias"];
        $iva = $_POST["iva"];
        $valorTotal = $_POST["valorTotal"];
        $fechaExpedicion = $_POST["fechaExpedicion"];

        $mesExpedicion = trim($_POST["mesExpedicion"]) == "" ? null : trim($_POST["mesExpedicion"]);
        $formaDePago = trim($_POST["formaDePago"]) == "" ? null : trim($_POST["formaDePago"]);
        $financiera = trim($_POST["financiera"]) == "" ? null : trim($_POST["financiera"]);

        // $numCotAseg = null; 
        // if(isset($_POST["numcotaseg"])){
        //     $numCotAseg = $_POST["numcotaseg"] == "" || $_POST["numcotaseg"] == null ? "" : $_POST["numcotaseg"];
        // }
        

        $carpeta = $_POST["carpeta"];
        $observaciones = $_POST["observaciones"];
        $fechaCreacion = $_POST["fechaCreacion"];

        $fechaActualizacion = null;

        // preparar valores numericos para ser insertados sin puntos ni comas. START
        if ($valor_cotizacion !== 0 && $valor_cotizacion != "null" && $valor_cotizacion != "0") {
            $valor_cotizacion = str_replace(',', '', $valor_cotizacion);
        }
        $prima_sin_iva = str_replace(',', '', $prima_sin_iva);
        $gastos = str_replace(',', '', $gastos);
        $asistencias = str_replace(',', '', $asistencias);
        $iva = str_replace(',', '', $iva);
        $valorTotal = trim(str_replace(['$', '.', ','], '', $valorTotal));

        // preparar valores numericos para ser insertados sin puntos ni comas. END

        // Prepara la consulta
        $result = $enlace->prepare("SELECT COALESCE(MAX(id_oportunidad), 0) + 1 AS next_id FROM oportunidades");

        if ($result->execute()) {
            $result->bind_result($next_id);
            $result->fetch();
            $next_id = $next_id ?? 0; // Asegúrate de que no sea null
        } else {
            die("Error en la ejecución de la consulta: " . $result->error);
        }
        $result->close();
        // Imprime el próximo ID para verificar

        $query = "INSERT INTO oportunidades (
            id_oportunidad, id_cotizacion, valor_cotizacion, mes_oportunidad, canal_oportunidad, asesor_freelance, 
            id_user_freelance, ramo, placa, oneroso, aseguradora, analista_comercial, 
            id_analista_comercial, estado, razon_negocio_perdido, otra_razon_negocio_perdido, no_poliza, asegurado, id_asegurado, prima_sin_iva, 
            asist_otros, gastos, iva, valor_total, fecha_expedicion, mes_expedicion, 
            forma_pago, financiera, carpeta, observaciones, id_oferta, id_cot_aseguradora, fecha_creacion, fecha_actualizacion
        ) VALUES (
            null, 
            $idCotizacion, 
            $valor_cotizacion, 
            '$mesOportunidad', 
            '$canalOportunidad', 
            '$asesor_freelance', 
            $id_user_freelance, 
            '$ramo', 
            '$placa', 
            '$oneroso', 
            '$aseguradora', 
            '$analista_comercial', 
            $id_analista_comercial, 
            '$estado',
            '$razonPerdidaOportunidad',
            '$otraRazon',
            '" . ($noPoliza === "" ? "NULL" : $noPoliza) . "', 
            '$asegurado', 
            $id_asegurado, 
            $prima_sin_iva, 
            $gastos, 
            $asistencias, 
            $iva, 
            $valorTotal, 
            " . ($fechaExpedicion == "null" ? "null" : "'$fechaExpedicion'") . ", 
            " . ($mesExpedicion === null ? "null" : "'$mesExpedicion'") . ", 
            " . ($formaDePago === null ? "null" : "'$formaDePago'") . ", 
            " . ($financiera === null ? "null" : "'$financiera'") . ", 
            '$carpeta', 
            '$observaciones', 
            $next_id,
            '$idCotAseguradora',
            '$fechaCreacion', 
            null
        )";
    } else {
        $noCotizacion = $_POST['idCotizacion'];
        $idOferta = $_POST['idOferta'];
        $idCotAseguradora = $_POST["idCotAseguradora"] == "" ? "" : $_POST["idCotAseguradora"];
        $fechaCreacion = mysqli_real_escape_string($enlace, $_POST['fechaCreacion']);
        $valor_cotizacion = $_POST['valor_cotizacion'];
        $mesOportunidad = mysqli_real_escape_string($enlace, $_POST['mesOportunidad']);
        $asesor_freelance = mysqli_real_escape_string($enlace, $_POST['asesor_freelance']);
        $ramo = mysqli_real_escape_string($enlace, $_POST['ramo']);
        $placa = mysqli_real_escape_string($enlace, $_POST['placa']);
        $oneroso = mysqli_real_escape_string($enlace, $_POST['oneroso']);
        $aseguradora = mysqli_real_escape_string($enlace, $_POST['aseguradora']);
        $analista_comercial = mysqli_real_escape_string($enlace, $_POST['analista_comercial']);
        $estado = mysqli_real_escape_string($enlace, $_POST['estado']);
        $asegurado = mysqli_real_escape_string($enlace, $_POST['asegurado']);
        $observaciones = isset($_POST['observaciones']) && $_POST['observaciones'] !== ""
            ? mysqli_real_escape_string($enlace, $_POST['observaciones'])
            : NULL;
        $id_asegurado = $_POST['id_asegurado'];
        $id_analista_comercial = $_POST['id_analista_comercial'];
        $id_user_freelance = empty($_POST["id_user_freelance"]) ? 0 : $_POST["id_user_freelance"];
        $fechaActualizacion = "NULL";
        $canalOportunidad = empty($_POST['canalOportunidad']) ? '' : $_POST['canalOportunidad'];
        $razonPerdidaOportunidad = empty($_POST['razonPerdidaOportunidad']) ? '' : $_POST['razonPerdidaOportunidad'];
        $otraRazon = empty($_POST['otraRazon']) ? '' : $_POST['otraRazon'];

        $query = "INSERT INTO oportunidades (id_oportunidad, id_cotizacion, valor_cotizacion, mes_oportunidad, canal_oportunidad, asesor_freelance, id_user_freelance, ramo, placa, oneroso, aseguradora, analista_comercial, id_analista_comercial, estado, razon_negocio_perdido, otra_razon_negocio_perdido, no_poliza, asegurado, id_asegurado, prima_sin_iva, asist_otros, gastos, iva, valor_total, fecha_expedicion, mes_expedicion, forma_pago, financiera, carpeta, observaciones, id_oferta, id_cot_aseguradora, fecha_creacion, fecha_actualizacion) VALUES (null, $noCotizacion, $valor_cotizacion, '$mesOportunidad', '$canalOportunidad', '$asesor_freelance', $id_user_freelance, '$ramo', '$placa', '$oneroso', '$aseguradora', '$analista_comercial', $id_analista_comercial, '$estado', '$razonPerdidaOportunidad', '$otraRazon', null, '$asegurado', $id_asegurado ,null, null, null, null, null, null, null, null, null, null, '$observaciones', $idOferta, '$idCotAseguradora', '$fechaCreacion', $fechaActualizacion)";
    }

    $stmt = $enlace->prepare($query);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $enlace->error);
    }
    if (!$stmt->execute()) {
        die("Error en la ejecución de la consulta: " . $stmt->error);
    }
    $rows = $stmt->affected_rows;
    if ($rows > 0 && !isset($_POST["manual"])) {

        $query2 = "UPDATE ofertas SET id_oportunidad = $stmt->insert_id WHERE id_oferta = $idOferta";
        $stmt2 = $enlace->prepare($query2);

        if ($stmt2 === false) {
            die("Error en la preparación de la consulta: " . $enlace->error);
        }
        
        if (!$stmt2->execute()) {
            die("Error en la ejecución de la consulta: " . $stmt2->error);
        }
        $rows2 = $stmt2->affected_rows;
        if ($rows2 > 0) {
            $data = array("status" => "success", "code" => 1, "message" => "Oportunidad Insertada Correctamente", "inserted_id" => $stmt->insert_id, "offert_updated" => $idOferta);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            $data = array("status" => "failed", "code" => 0, "message" => "Oportunidad insertada pero con errores al modificar la oferta");
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    } else if ($rows > 0 && isset($_POST["manual"])) {
        $data = array("status" => "success", "code" => 1, "message" => "Oportunidad Insertada Correctamente", "inserted_id" => $next_id, "offert_updated" => $idOferta);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        $data = array("status" => "failed", "code" => 0, "message" => "Oportunidad no insertada");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
