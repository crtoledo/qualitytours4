<?php
 require_once 'connection.php';
    $Db = new db();
    $conn = $Db->open();
    setlocale ( LC_TIME, 'spanish' );
    $fecha = strftime(date('Y/m/d'));
    $id_usu = $_POST['id_usu'];
    $detalle= $_POST['mensaje'];
    $id_cli = $_POST['id_cli'];
   
    

$res=  pg_query("INSERT INTO comentario(id_usu, cli_id_usu, fecha_com, detalle_com, estado_com)
    VALUES (".$id_usu.",".$id_cli.",'".$fecha."','".$detalle."','t')");


?>
