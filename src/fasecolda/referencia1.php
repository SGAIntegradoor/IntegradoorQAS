<?php
include_once '../../config/dbconfig.php';

if (isset($_POST['dataString'], $_POST['clasveh'], $_POST['MarcaVeh'])) {
    $id = $_POST['dataString'];
    $clasveh = $_POST['clasveh'];
    $MarcaVeh = $_POST['MarcaVeh'];

    // Usa variable correcta en el bind (corregido: $clasveh en lugar de $claseveh)
    $stmt = $DB_con->prepare("SELECT * FROM fasecolda WHERE clase = :Clase AND marca = :MarcaVeh AND `$id` <> 0 GROUP BY referencia1 ORDER BY id_fasecolda");
    $stmt->execute(array(':Clase' => $clasveh, ':MarcaVeh' => $MarcaVeh));

    $output = '<select name="lineaVeh" class="lineaVeh" required>';
    $output .= '<option value="">Seleccione la Linea</option>';

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $referencia = htmlspecialchars($row['referencia1']);
        $output .= "<option value=\"{$referencia}\">{$referencia}</option>";
    }

    $output .= '</select>';

    echo $output;
} else {
    echo '<select name="lineaVeh" class="lineaVeh" required><option value="">Seleccione la Linea</option></select>';
}
?>