<?php

//ESTE ARCHIVO SE MANTIENE COMO REFERENCIA
//ELIMINAR ESTE ARCHIVO CUANDO LA BUSQUEDA
//ESTE 100% TERMINADA

    //BUSQUEDA PARA EL MENU NAV BAR (BUSQUEDA GENERAL)
    require_once 'connection.php';
    $Db = new db();
    $conn = $Db->open();

    //VARIABLE Q INDICA CUANTAS QUERYS FUERON EXITOSAS
    $query_ok = 0;
    
    //STRINGS DE LAS DISTINSTAS BUSQUEDAS
    $string1 =
    "select cliente.id_usu as id_cliente, cliente.nombre_cli as nombre_cliente, ubicacion.region_ubi as region, ubicacion.ciudad_ubi as ciudad, cliente.visitas_cli as visitas, coalesce(AVG(calificacion.valor_cal),0) as promedio
from cliente
join ubicacion on cliente.id_usu = ubicacion.id_usu
full join calificacion on cliente.id_usu = calificacion.cli_id_usu
WHERE cliente.nombre_cli ilike '%".$_REQUEST['string']."%' 
GROUP BY cliente.id_usu, ubicacion.region_ubi, ubicacion.ciudad_ubi, cliente.visitas_cli 
ORDER BY promedio desc, visitas";
    
    $string2 = "select cliente.id_usu as id_cliente, cliente.nombre_cli as nombre_cliente, ubicacion.region_ubi as region, ubicacion.ciudad_ubi as ciudad, cliente.visitas_cli as visitas, coalesce(AVG(calificacion.valor_cal),0) as promedio
from cliente
join ubicacion on cliente.id_usu = ubicacion.id_usu
full join calificacion on cliente.id_usu = calificacion.cli_id_usu
WHERE ubicacion.region_ubi ilike '%".$_REQUEST['string']."%' 
GROUP BY cliente.id_usu, ubicacion.region_ubi, ubicacion.ciudad_ubi, cliente.visitas_cli 
ORDER BY promedio desc, visitas";
    
    
    $string3 = "select cliente.id_usu as id_cliente, cliente.nombre_cli as nombre_cliente, ubicacion.region_ubi as region, ubicacion.ciudad_ubi as ciudad, cliente.visitas_cli as visitas, coalesce(AVG(calificacion.valor_cal),0) as promedio
from cliente
join ubicacion on cliente.id_usu = ubicacion.id_usu
full join calificacion on cliente.id_usu = calificacion.cli_id_usu
WHERE ubicacion.ciudad_ubi ilike '%".$_REQUEST['string']."%' 
GROUP BY cliente.id_usu, ubicacion.region_ubi, ubicacion.ciudad_ubi, cliente.visitas_cli 
ORDER BY promedio desc, visitas";
    
    $resu1 = pg_query($conn,$string1);
    $res1 = pg_fetch_all($resu1);
    
    $resu2 = pg_query($conn,$string2);
    $res2 = pg_fetch_all($resu2);

    $resu3 = pg_query($conn,$string3);
    $res3 = pg_fetch_all($resu3);
   
    if (is_array($res1) && isset($res1[0]))
    {    
        echo json_encode($res1);
        $query_ok++;
    }
        
    if (is_array($res2) && isset($res2[0]))
    {
        echo "SEPARADOR".json_encode($res2);
        $query_ok++;
    }
    
    if (is_array($res3) && isset($res3[0]))
    {
        echo "SEPARADOR".json_encode($res3);
        $query_ok++;
    }
    
    if ($query_ok == 0) //NO SE ENCONTRARON RESULTADOS
    {
        return 'no hay resultados';
    }
    else
        echo "SEPARADOR".
    
    $Db->close($conn);
?>