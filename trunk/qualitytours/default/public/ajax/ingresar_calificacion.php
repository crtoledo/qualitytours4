<?php
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();
setlocale ( LC_TIME, 'spanish' );
$fecha = strftime(date('d/m/Y'));
$cal = $_REQUEST['cal'];
$id_usu= $_REQUEST['id_usu'];
$id_cli = $_REQUEST['id_cli'];




$res = pg_query($conn,"INSERT INTO calificacion(id_usu, cli_id_usu, valor_cal, fecha_cal) VALUES (".$id_usu.",".$id_cli.",".$cal.",'".$fecha."')");

if($res)
{
    echo 'exito';
}
else
{
    echo 'error';
}
$Db->close($conn);


?>