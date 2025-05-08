<?php
require_once "../../../../config/dbconfig.php";
$enlace->set_charset("utf8mb4");

class Asesor {
    public $asesor_id;
    public $asesor;
    public $fecha_ingreso;
    public $estado_usuario;
    public $analista;
    public $meses = [
        'mes1' => ['cotizaciones' => 0, 'negocios' => 0],
        'mes2' => ['cotizaciones' => 0, 'negocios' => 0],
        'mes3' => ['cotizaciones' => 0, 'negocios' => 0]
    ];
}

// Obtener parámetros (formato compatible con PHP 7.2)
$anio     = isset($_POST["anio"]) ? $_POST["anio"] : null;
$mes      = isset($_POST["mes"]) ? $_POST["mes"] : null;
$asesor   = isset($_POST["asesor"]) ? $_POST["asesor"] : null;
$analista = isset($_POST["analista"]) ? $_POST["analista"] : null;
$ramo     = isset($_POST["ramo"]) ? $_POST["ramo"] : null;

// =======================================
// 1. Determinar rango de meses
// =======================================
function getRangoFechas($anio = null, $mes = null) {
    $fechas = [];
    $hoy = new DateTime();

    if (!empty($anio)) {
        $anio = (int)$anio;
    } else {
        $anio = null;
    }

    if (!empty($mes)) {
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT); // Asegura formato "05"
    } else {
        $mes = null;
    }

    if (!is_null($anio) && !is_null($mes)) {
        $fechaBase = DateTime::createFromFormat('Y-m', "$anio-$mes");
    } elseif (!is_null($mes)) {
        $fechaBase = DateTime::createFromFormat('Y-m', $hoy->format('Y') . "-$mes");
    } elseif (!is_null($anio)) {
        $fechaBase = DateTime::createFromFormat('Y-m', "$anio-" . $hoy->format('m'));
    } else {
        $fechaBase = $hoy;
    }

    if (!$fechaBase) {
        throw new Exception("Error al crear la fecha base con año: $anio y mes: $mes");
    }

    for ($i = 0; $i < 3; $i++) {
        $start = (clone $fechaBase)->modify("-$i month")->modify('first day of this month')->setTime(0, 0, 0);
        $end   = (clone $fechaBase)->modify("-$i month")->modify('last day of this month')->setTime(23, 59, 59);
        $fechas["mes" . ($i + 1)] = [
            'inicio' => $start->format('Y-m-d H:i:s'),
            'fin'    => $end->format('Y-m-d H:i:s')
        ];
    }

    return $fechas;
}

// =======================================
// 2. Obtener asesores sin get_result()
// =======================================
function getAsesores($asesor = null, $analista = null) {
    global $enlace;

    $sql = "SELECT u.id_usuario, CONCAT(u.usu_nombre, ' ', u.usu_apellido) AS asesor,
            DATE_FORMAT(u.usu_fch_creacion, '%d/%m/%Y') AS fecha_ingreso,
            CASE WHEN u.usu_estado = 1 THEN 'Activo' ELSE 'Inactivo' END AS estado_usuario,
            af.nombre_analista AS analista
        FROM usuarios u
        LEFT JOIN analistas_freelances af ON af.id_usuario = u.usu_documento
        WHERE u.id_rol IN (19, 12, 11, 10, 1)";
    
    $params = [];
    $types  = "";

    if ($analista) {
        $sql .= " AND af.id_analista = ?";
        $params[] = $analista;
        $types .= "s";
    }

    if ($asesor) {
        $sql .= " AND u.id_usuario = ?";
        $params[] = $asesor;
        $types .= "i";
    }

    $stmt = $enlace->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if($stmt->execute()){
        // La consulta se ejecutó correctamente
        echo "se ejecuto";
    } else {
        // Manejo de errores en caso de fallo en la ejecución
        echo "Error en la consulta: " . $stmt->error;
        return null;
    }




    $stmt->store_result();

    // Definir variables explícitamente para bind_result
    $id_usuario = $asesorNombre = $fecha_ingreso = $estado_usuario = $analistaNombre = "";

    $stmt->bind_result($id_usuario, $asesorNombre, $fecha_ingreso, $estado_usuario, $analistaNombre);

    $asesores = [];

    while ($stmt->fetch()) {
        $asesorObj = new Asesor();
        $asesorObj->asesor_id = $id_usuario;
        $asesorObj->asesor = $asesorNombre;
        $asesorObj->fecha_ingreso = $fecha_ingreso;
        $asesorObj->estado_usuario = $estado_usuario;
        $asesorObj->analista = $analistaNombre;
        $asesores[] = $asesorObj;
    }

    $stmt->close();
    return $asesores;
}
