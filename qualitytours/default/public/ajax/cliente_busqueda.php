<?php
//Busca el nombre y id de los cliente que contengan el string dado
//el string se pasa por la url GET
//?string=xxx
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

$resu = pg_query($conn,"select nombre_cli,id_usu from cliente where nombre_cli ilike '%".$_REQUEST['string']."%'");
$res = pg_fetch_all($resu);

if (is_array($res) && isset($res[0]))
{    
    echo json_encode($res); 
}
else
    return null;
$Db->close($conn);
?>
