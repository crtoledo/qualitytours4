<?php
 require_once 'connection.php';
    $Db = new db();
    $conn = $Db->open();
    setlocale ( LC_TIME, 'spanish' );
    $fecha = strftime(date('Y/m/d'));
    $id_usu = $_POST['id_usu'];
    $comentario= $_POST['comentario'];
    $id_cli = $_POST['id_cli'];
    $nombre = $_POST['nombre'];
    
   
  

$res=  pg_query("INSERT INTO comentario(id_usu, cli_id_usu, fecha_com, detalle_com, estado_com)
    VALUES (".$id_usu.",".$id_cli.",'".$fecha."','".$comentario."','t')");


$Db->close($conn);
?>
<div class="span12">
    <div class="comentarios">
    
   
    
    <table class="table table-hover">
        <tr>
            <td><?php echo "<b>".$nombre."</b>"; ?></td>
            <td><div align="right"><?php echo "<b>".$fecha."</b>";?></div></td>
        </tr>
    </table>
    <table>
    <tr>
        <td> <div align="left"> <?php echo $comentario; ?> </div></td>
        </tr>
        
    </table> 
</div>
</div>