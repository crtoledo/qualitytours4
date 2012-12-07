<?php

//BUSQUEDA PARA EL MENU NAV BAR (BUSQUEDA GENERAL)CRITERIO 5 - busqueda categorias (INGLES)
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

//centros turisticos que contengan el string en la region
$string =
"select count (categoria.nombre_cat_eng) as categorias_encontradas, categoria.id_usu as id_cliente, ubicacion.region_ubi as region, ubicacion.ciudad_ubi as ciudad, cliente.nombre_cli as nombre_cliente, cliente.visitas_cli as visitas, coalesce(AVG(calificacion.valor_cal),0) as promedio
from categoria
join ubicacion on categoria.id_usu = ubicacion.id_usu
join cliente on categoria.id_usu = cliente.id_usu
full join calificacion on categoria.id_usu = calificacion.cli_id_usu
where categoria.nombre_cat_eng ilike '%".$_REQUEST['string']."%' and categoria.estado_cat = T
group by id_cliente, region, ciudad, nombre_cliente, visitas
order by categorias_encontradas desc, promedio desc, visitas desc
limit 20;";

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



