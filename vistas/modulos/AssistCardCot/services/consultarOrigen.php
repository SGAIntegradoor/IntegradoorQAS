<?php
// Incluir el archivo de configuración de la base de datos
require_once "../../../../config/dbconfig.php";

// Consulta utilizando PDO
try {
    $query = "SELECT * FROM paises_assistCard";
    $stmt = $DB_con->query($query);
} catch (PDOException $e) {
    echo "Error al ejecutar la consulta: " . $e->getMessage();
    exit; // Salir del script si hay error en la consulta
}
?>

<select id="lugarOrigen" class="form-control">
    <option value="000">Lugar de Origen *</option>
    <option value="46" selected>Colombia</option>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <option value="<?php echo $row['idpaises']; ?>"><?php echo $row['paises']; ?></option>
    <?php } ?>
</select>