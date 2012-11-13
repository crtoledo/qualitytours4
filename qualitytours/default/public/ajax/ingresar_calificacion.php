<?php
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();
setlocale ( LC_TIME, 'spanish' );
$fecha = strftime(date('d/m/Y'));
$cal = $_REQUEST['data.cal[0]'];
$id_usu= $_REQUEST['data.id_usu[0]'];
$id_cli = $_REQUEST['data.id_cli[0]'];




$res = pg_query($conn,"INSERT INTO calificacion(id_usu, cli_id_usu, valor_cal, fecha_cal) VALUES (".$id_usu.",".$id_cli.",".$cal.",'".$fecha."')");

if($res)
{
    return false;
}
else
{
    return true;
}
$Db->close($conn);


?>