<?php

require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

if (isset($_REQUEST['opcion']))
    $tipo = $_REQUEST['opcion'];
else
    $tipo = 'no_seleccionado';

$usuariobuscar = $_REQUEST['string'];

if ($tipo == "no_seleccionado") {
    $res = pg_query($conn, "select solicitud.id, cliente.id_usu, cliente.username_usu, solicitud.fecha_sol, solicitud.estado_sol, solicitud.tipo_sol, solicitud.mail_sol
from cliente join solicitud on cliente.id_usu=solicitud.id_usu 
WHERE activo_sol = true and username_usu ilike '%" . $usuariobuscar . "%'");
    $arr = pg_fetch_all($res);
} else {
    $res = pg_query($conn, "select solicitud.id, cliente.id_usu, cliente.username_usu, solicitud.fecha_sol, solicitud.estado_sol, solicitud.tipo_sol, solicitud.mail_sol
from cliente join solicitud on cliente.id_usu=solicitud.id_usu 
WHERE activo_sol = true and estado_sol = '". $tipo ."' and username_usu ilike '%" . $usuariobuscar . "%'");
    $arr = pg_fetch_all($res);
}

if (is_array($arr) && isset($arr[0])) {
    echo json_encode($arr);
}
else
    return null;
$Db->close($conn);
?>