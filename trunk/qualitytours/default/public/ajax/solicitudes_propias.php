<?php
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

$resu = pg_query($conn,"select fecha_sol, estado_sol, tipo_sol, activo_sol from solicitud where id_usu=".$_REQUEST['string']." and activo_sol= false");
$res = pg_fetch_all($resu);

if (is_array($res) && isset($res[0]))
{    
    echo json_encode($res); 
}
else
    return null;
$Db->close($conn);
?>