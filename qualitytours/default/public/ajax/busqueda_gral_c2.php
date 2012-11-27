<?php

//BUSQUEDA PARA EL MENU NAV BAR (BUSQUEDA GENERAL)CRITERIO 1 - regiones
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();


//centros turisticos que contengan el string en la region
$string =
"select cliente.id_usu as id_cliente, cliente.nombre_cli as nombre_cliente, ubicacion.region_ubi as region, ubicacion.ciudad_ubi as ciudad, cliente.visitas_cli as visitas, coalesce(AVG(calificacion.valor_cal),0) as promedio
from cliente
join ubicacion on cliente.id_usu = ubicacion.id_usu
full join calificacion on cliente.id_usu = calificacion.cli_id_usu
WHERE ubicacion.region_ubi ilike '%".$_REQUEST['string']."%' 
GROUP BY cliente.id_usu, ubicacion.region_ubi, ubicacion.ciudad_ubi, cliente.visitas_cli 
ORDER BY promedio desc, visitas";

$resu = pg_query($conn, $string);
$res = pg_fetch_all($resu);

if (is_array($res) && isset($res[0])) 
{
    echo json_encode($res);
} 
else 
{
    return null;
}

$Db->close($conn);
?>
