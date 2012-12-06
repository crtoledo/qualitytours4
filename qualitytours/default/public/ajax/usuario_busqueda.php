<?php

require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

if (isset($_REQUEST['opcion']))
    $tipo = $_REQUEST['opcion'];
else
    $tipo='no_seleccionado';

$usuariobuscar = $_REQUEST['string'];

if ($tipo == "no_seleccionado") {
    $res = pg_query($conn, "select * from usuario where username_usu ilike '%" . $usuariobuscar . "%' and estado_usu=true");
    $arr = pg_fetch_all($res);
} else {
    $res = pg_query($conn, "select * from usuario where username_usu ilike '%" . $usuariobuscar . "%' and rol_usu='" . $tipo . "' and estado_usu=true");
    $arr = pg_fetch_all($res);
}

if (is_array($arr) && isset($arr[0])) {
    echo json_encode($arr);
}
else
    return null;
$Db->close($conn);
?>