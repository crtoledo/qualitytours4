<?php
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

$resu = pg_query($conn,"select count(*) as datos from solicitud where mail_sol = true and estado_sol='Esperando' and activo_sol = true
union all select count(*) from solicitud where modificaciones_sol= true and estado_sol='Pendiente' and activo_sol= true
union all select count(*) from solicitud where estado_sol='Pendiente' and activo_sol= true and modificaciones_sol=false
union all select count(*) from cliente where fecha_fin_sus= CURRENT_DATE  and estado_usu= true");
$res = pg_fetch_all($resu);

if (is_array($res) && isset($res[0]))
{    
    echo json_encode($res); 
}
else
    return null;
$Db->close($conn);
?>
