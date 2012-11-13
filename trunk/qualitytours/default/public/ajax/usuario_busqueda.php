<?php
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

$tipo = $_REQUEST['opcion'];
$usuariobuscar = $_REQUEST['string'];

if ($tipo == "")
{
$res = pg_query($conn,"select * from usuario where username_usu ilike '%".$usuariobuscar."%'");
$arr = pg_fetch_all($res);
}
else
{
$res = pg_query($conn,"select * from usuario where username_usu ilike '%".$usuariobuscar."%' and rol_usu='".$tipo ."'");
$arr = pg_fetch_all($res);    
}

if (is_array($arr) && isset($arr[0]))
{    
   echo json_encode($arr); 
}
else
    return null;
$Db->close($conn);
?>
