<?php

/*
 * 1: Solicitud que esta pendiente de revision
 * 2: Indica que el cliente ya mando el mail
 * 3: La solicitud fue modificada
 * 4: Suscripcion terminada
 */

require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

$tipo = $_REQUEST['opcion'];

if ($tipo == "1") {
    $resu = pg_query($conn, "select solicitud.id, cliente.id_usu, cliente.username_usu, solicitud.fecha_sol, solicitud.estado_sol, solicitud.tipo_sol 
from solicitud join cliente on solicitud.id_usu= cliente.id_usu
where activo_sol= true and estado_sol ='Pendiente' and modificaciones_sol=false");
    $res = pg_fetch_all($resu);
} else if ($tipo == "2") {
    $resu = pg_query($conn, "select solicitud.id, cliente.id_usu, cliente.username_usu, solicitud.fecha_sol, solicitud.estado_sol, solicitud.tipo_sol  
from solicitud join cliente on solicitud.id_usu= cliente.id_usu
where mail_sol= true and activo_sol= true and estado_sol ='Esperando'");
    $res = pg_fetch_all($resu);
} else if ($tipo == "3") {
    $resu = pg_query($conn, "select solicitud.id, cliente.id_usu, cliente.username_usu, solicitud.fecha_sol, solicitud.estado_sol, solicitud.tipo_sol 
from solicitud join cliente on solicitud.id_usu= cliente.id_usu
where modificaciones_sol=true and activo_sol= true and estado_sol ='Pendiente'");
    $res = pg_fetch_all($resu);
} else if ($tipo == "4") {
    $resu = pg_query($conn, "select cliente.id_usu, solicitud.id, cliente.nombre_usu, cliente.username_usu, cliente.nombre_cli, cliente.email_usu,cliente.telefono_cli, cliente.tipo_plan
from cliente join solicitud on cliente.id_usu = solicitud.id_usu where fecha_fin_sus <= CURRENT_DATE  and (estado_usu= true and activo_sol=true) and (tipo_plan='plus' or tipo_plan='normal')");
    $res = pg_fetch_all($resu);
}

if (is_array($res) && isset($res[0])) {
    echo json_encode($res);
}
else
    return null;
$Db->close($conn);
?>
