<?php
//require_on
require_once("../../config/dbconfig.php"); //Contiene funcion que conecta a la base de datos

if ($_POST['dataString'] && $clasveh = $_POST['clasveh']) {

    $id = $_POST['dataString'];

    $clasveh = $_POST['clasveh'];

    $anio_actual = date("Y") + 1;

    $sql = $DB_con->prepare("SELECT DISTINCT COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = 'fasecolda' and COLUMN_NAME  between '1970' and '" . $anio_actual . "'");
    $sql->execute();
    $rows = $sql->rowCount();

    //for para guardar la consulta en un arreglo
    for ($i = 0; $i < $rows; $i++) {
        $verConfig = $sql->fetch(PDO::FETCH_ASSOC);
        $CargaConfig[$i] = $verConfig["COLUMN_NAME"];
    }

    $cantidad = count($CargaConfig);

?>
    <option value="">Seleccione el Modelo</option><?php
                                                    for ($p = 0; $p < $cantidad; $p++) {
                                                    ?>
        <option value="<?php echo $CargaConfig[$p] ?>"><?php echo $CargaConfig[$p] ?></option>
<?php
                                                    }

                                                    // echo "</select>";


                                                }


?>