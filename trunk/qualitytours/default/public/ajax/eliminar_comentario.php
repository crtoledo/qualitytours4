<?php
 require_once 'connection.php';

    $Db = new db();
    $conn = $Db->open();
    
    $comentario = $_POST['id'];
     $res = pg_query("UPDATE comentario set estado_com ='f' WHERE id =".$comentario);
    
    $Db->close($conn);
    
    
?>
