<?php

session_start();

/* Conectar a la base de datos */
require_once("../modelos/conexion.php"); // Contiene función que conecta a la base de datos

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// id_cotizacion						
// aseguradora	
// valor_prima							
// id_cot_aseguradora	
// cob_terr_ev_nat_sbs				
// cob_hur_con_no_ele_sbs				
// cob_hur_con_ele_sbs				
// cob_tr_sbs				
// cob_acci_pers_sbs				
// cob_resp_civil_sbs				
// cob_asist_dom_sbs				
// cob_prod_plus_sbs				
// cob_inc_alz				
// cob_terr_alz				
// cob_rce_prop_alz				
// cob_asist_jur_alz				
// cob_asist_dom_alz				
// cob_hamccp_alz				
// cob_danos_agua_alz				
// cob_eve_nat_alz				
// cob_rce_fam_alz				
// cob_eve_elec_alz				
// cob_hur_alz				
// cob_tr_alz				
// cob_asis_mas_alz

try {
    $pdo = Conexion::conectar();

    $data = $_POST ?? null;
    $id_cotizacion = $data['id_cotizacion'] ?? null;
    $aseguradora = $data['aseguradora'] ?? null;
    $producto = $data['producto'] ?? null;
    $valor_prima = $data['valor_prima'] ?? null;
    $id_cot_aseguradora = $data['id_cot_aseguradora'] ?? null;
    $cob_terr_ev_nat_sbs = $data['cob_terr_ev_nat_sbs'] ?? null;
    $cob_hur_con_no_ele_sbs = $data['cob_hur_con_no_ele_sbs'] ?? null;
    $cob_hur_con_ele_sbs = $data['cob_hur_con_ele_sbs'] ?? null;
    $cob_tr_sbs = $data['cob_tr_sbs'] ?? null;
    $cob_acci_pers_sbs = $data['cob_acci_pers_sbs'] ?? null;
    $cob_resp_civil_sbs = $data['cob_resp_civil_sbs'] ?? null;
    $cob_asist_dom_sbs = $data['cob_asist_dom_sbs'] ?? null;
    $cob_prod_plus_sbs = $data['cob_prod_plus_sbs'] ?? null;
    $cob_inc_alz = $data['cob_inc_alz'] ?? null;
    $cob_terr_alz = $data['cob_terr_alz'] ?? null;
    $cob_rce_prop_alz = $data['cob_rce_prop_alz'] ?? null;
    $cob_asist_jur_alz = $data['cob_asist_jur_alz'] ?? null;
    $cob_asist_dom_alz = $data['cob_asist_dom_alz'] ?? null;
    $cob_hamccp_alz = $data['cob_hamccp_alz'] ?? null;
    $cob_danos_agua_alz = $data['cob_danos_agua_alz'] ?? null;
    $cob_eve_nat_alz = $data['cob_eve_nat_alz'] ?? null;
    $cob_rce_fam_alz = $data['cob_rce_fam_alz'] ?? null;
    $cob_eve_elec_alz = $data['cob_eve_elec_alz'] ?? null;
    $cob_hur_alz = $data['cob_hur_alz'] ?? null;
    $cob_tr_alz = $data['cob_tr_alz'] ?? null;
    $cob_asis_mas_alz = $data['cob_asis_mas_alz'] ?? null;

    $stmt = $pdo->prepare("INSERT INTO ofertas_hogar (id, id_cotizacion, aseguradora, producto, valor_prima, id_cot_aseguradora, cob_terr_ev_nat_sbs, cob_hur_con_no_ele_sbs, cob_hur_con_ele_sbs, cob_tr_sbs, cob_acci_pers_sbs, cob_resp_civil_sbs, cob_asist_dom_sbs, cob_prod_plus_sbs, cob_inc_alz, cob_terr_alz, cob_rce_prop_alz, cob_asist_jur_alz, cob_asist_dom_alz, cob_hamccp_alz, cob_danos_agua_alz, cob_eve_nat_alz, cob_rce_fam_alz, cob_eve_elec_alz, cob_hur_alz, cob_tr_alz, cob_asis_mas_alz ) 
                          VALUES (null, :id_cotizacion, :aseguradora, :producto, :valor_prima, :id_cot_aseguradora, :cob_terr_ev_nat_sbs, :cob_hur_con_no_ele_sbs, :cob_hur_con_ele_sbs, :cob_tr_sbs, :cob_acci_pers_sbs, :cob_resp_civil_sbs, :cob_asist_dom_sbs, :cob_prod_plus_sbs, :cob_inc_alz, :cob_terr_alz, :cob_rce_prop_alz, :cob_asist_jur_alz, :cob_asist_dom_alz, :cob_hamccp_alz, :cob_danos_agua_alz, :cob_eve_nat_alz, :cob_rce_fam_alz, :cob_eve_elec_alz, :cob_hur_alz, :cob_tr_alz, :cob_asis_mas_alz)");

    $stmt->bindParam(':id_cotizacion', $id_cotizacion);
    $stmt->bindParam(':aseguradora', $aseguradora);
    $stmt->bindParam(':producto', $producto);
    $stmt->bindParam(':valor_prima', $valor_prima);
    $stmt->bindParam(':id_cot_aseguradora', $id_cot_aseguradora);
    $stmt->bindParam(':cob_terr_ev_nat_sbs', $cob_terr_ev_nat_sbs);
    $stmt->bindParam(':cob_hur_con_no_ele_sbs', $cob_hur_con_no_ele_sbs);
    $stmt->bindParam(':cob_hur_con_ele_sbs', $cob_hur_con_ele_sbs);
    $stmt->bindParam(':cob_tr_sbs', $cob_tr_sbs);
    $stmt->bindParam(':cob_acci_pers_sbs', $cob_acci_pers_sbs);
    $stmt->bindParam(':cob_resp_civil_sbs', $cob_resp_civil_sbs);
    $stmt->bindParam(':cob_asist_dom_sbs', $cob_asist_dom_sbs);
    $stmt->bindParam(':cob_prod_plus_sbs', $cob_prod_plus_sbs);
    $stmt->bindParam(':cob_inc_alz', $cob_inc_alz);
    $stmt->bindParam(':cob_terr_alz', $cob_terr_alz);
    $stmt->bindParam(':cob_rce_prop_alz', $cob_rce_prop_alz);
    $stmt->bindParam(':cob_asist_jur_alz', $cob_asist_jur_alz);
    $stmt->bindParam(':cob_asist_dom_alz', $cob_asist_dom_alz);
    $stmt->bindParam(':cob_hamccp_alz', $cob_hamccp_alz);
    $stmt->bindParam(':cob_danos_agua_alz', $cob_danos_agua_alz);
    $stmt->bindParam(':cob_eve_nat_alz', $cob_eve_nat_alz);
    $stmt->bindParam(':cob_rce_fam_alz', $cob_rce_fam_alz);
    $stmt->bindParam(':cob_eve_elec_alz', $cob_eve_elec_alz);
    $stmt->bindParam(':cob_hur_alz', $cob_hur_alz);
    $stmt->bindParam(':cob_tr_alz', $cob_tr_alz);
    $stmt->bindParam(':cob_asis_mas_alz', $cob_asis_mas_alz);

    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId(); // Obtener el último ID insertado
        echo json_encode(["success" => true, "message" => "Guardado correctamente", "last_id" => $lastId]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(["success" => false, "message" => "Error al guardar", "error" => $errorInfo[2]]);
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

?>