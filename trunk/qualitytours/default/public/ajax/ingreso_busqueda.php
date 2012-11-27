<?php

//INGRESO DE LA BUSQUEDA
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

//VARIABLE PARA SABER QUE FUE LO QUE PASO
$estado_query = "FALLO";

//FECHA
setlocale ( LC_TIME, 'spanish' );
$fecha = strftime(date('Y/m/d'));

$string_select = "select * from BUSQUEDA where texto_bus ilike '".$_REQUEST['string']."'";
$resu_select = pg_query($conn, $string_select);
$select_count = pg_affected_rows($resu_select);

if ($select_count == 0) // NO EXISTE LA BUSQUEDA EN LA BD 
{
    //STRING para el ingreso de una busqueda nueva
    $string_insert =
        "INSERT INTO busqueda(
                id_usu, contador_bus, texto_bus, estado_bus, fecha_bus, resultados_bus)
        VALUES (".$_REQUEST['id_usu'].", 1, '".$_REQUEST['string']."', true, '".$fecha."', ".$_REQUEST['resultados'].");
        ";
    $resu_insert = pg_query($conn,$string_insert);
    if (pg_affected_rows($resu_insert) > 0)
        $estado_query = "INSERT";
    else
        $estado_query = "FALLO INSERT";
   
}
else //YA EXISTE LA BUSQUEDA EN LA BD
{
    //$res_select = pg_fetch_all($resu_select);
    $res_select = pg_fetch_row($resu_select);
    
    $string_update = "
        UPDATE busqueda
        SET contador_bus=".($res_select[2]+1)." ,resultados_bus=".$_REQUEST['resultados']."
        WHERE id=".$res_select[0].";
        ";
    $resu_update = pg_query($conn,$string_update);
    if (pg_affected_rows($resu_update) > 0)
        $estado_query = "UPDATE";
    else
        $estado_query = "FALLO UPDATE";
}




return $estado_query;

$Db->close($conn);
?>
