<?php
    //BUSQUEDA PARA EL MENU NAV BAR (BUSQUEDA GENERAL)
    
    require_once 'connection.php';
    $Db = new db();
    $conn = $Db->open();

    //VARIABLE Q INDICA CUANTAS QUERYS FUERON EXITOSAS
    $query_ok = 0;
    
    //Limpiar comillas simples
    $_REQUEST['string'] = str_replace("'", '', $_REQUEST['string']);
    
    //RECIBIMOS EL LENGUAJE
    $leng = $_REQUEST['leng'];
    
    //STRINGS DE LAS DISTINSTAS BUSQUEDAS
    $string1 = "select nombre_cli, id_usu from cliente where nombre_cli ilike '%".$_REQUEST['string']."%' and estado_usu = T order by visitas_cli desc limit 5";
    $string2 = "select DISTINCT region_ubi from ubicacion where region_ubi ilike '%".$_REQUEST['string']."%' limit 5";
    $string3 = "select DISTINCT ciudad_ubi from ubicacion where ciudad_ubi ilike '%".$_REQUEST['string']."%' limit 5";
    
    //STRING DE BUSQUEDA POR CATEGORIA (ESPANOL-INGLES)
    if ($leng == "es")
    {
        $string4 = "select distinct nombre_cat, count (categoria.nombre_cat) from categoria where categoria.nombre_cat ilike '%" . $_REQUEST['string'] . "%'
    group by nombre_cat
    order by count desc 
    limit 5";
    }
    if ($leng == "en")
    {
        $string4 = "select distinct nombre_cat_eng, count (categoria.nombre_cat_eng) from categoria where categoria.nombre_cat_eng ilike '%" . $_REQUEST['string'] . "%'
    group by nombre_cat_eng
    order by count desc 
    limit 5";
    }

$resu1 = pg_query($conn,$string1);
    $res1 = pg_fetch_all_columns($resu1);
    
    $resu2 = pg_query($conn,$string2);
    $res2 = pg_fetch_all_columns($resu2);

    $resu3 = pg_query($conn,$string3);
    $res3 = pg_fetch_all_columns($resu3);
    
    $resu4 = pg_query($conn,$string4);
    $res4 = pg_fetch_all_columns($resu4);
   
    if (is_array($res1) && isset($res1[0]))
    {    
        //echo json_encode($res1);
        foreach ($res1 as $valor)
            echo $valor.",";
        $query_ok++;
    }
        
    if (is_array($res2) && isset($res2[0]))
    {
        //echo "SEPARADOR".json_encode($res2);
        //echo "SEPARADOR";
        foreach ($res2 as $valor)
            echo $valor.",";
        $query_ok++;
    }
    
    if (is_array($res3) && isset($res3[0]))
    {
        //echo "SEPARADOR".json_encode($res3);
        //echo "SEPARADOR";
        foreach ($res3 as $valor)
            echo $valor.",";
        $query_ok++;
    }
    
    if (is_array($res4) && isset($res4[0]))
    {
        //echo "SEPARADOR".json_encode($res3);
        //echo "SEPARADOR";
        foreach ($res4 as $valor)
            echo $valor.",";
        $query_ok++;
    }
    
    if ($query_ok == 0) //NO SE ENCONTRARON RESULTADOS
    {
        return 'no hay resultados';
    }
    
    $Db->close($conn);
?>
