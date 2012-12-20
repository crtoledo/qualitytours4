<?php
 require_once 'connection.php';
    $Db = new db();
    $conn = $Db->open();
    setlocale ( LC_TIME, 'spanish' );
    $fecha = strftime(date('Y/m/d'));
    $id_usu = $_POST['id_usu'];
    $comentario= $_POST['comentario'];
    $id_ser = $_POST['id_ser'];
    $nombre = $_POST['nombre'];
    
   
  

$res=  pg_query("INSERT INTO comentario(id_usu, id_ser, fecha_com, detalle_com, estado_com)
    VALUES (".$id_usu.",".$id_ser.",'".$fecha."','".$comentario."','t')");

$ultimo1 = pg_query("SELECT LAST_VALUE as id FROM comentario_id_seq"); 
$ultimo2 = pg_fetch_array($ultimo1);
$ultimoid = $ultimo2["id"];

$Db->close($conn);
?>
 <div class="service_list" id="service<?=$ultimoid ?>" data="<?=$ultimoid ?>">  
            <table class="table table-hover span12" style="margin-left: 0px;">
                <tr>
                    <td><?php echo "<b>".$nombre."</b>"; ?>
                <br>
                <div align="left"> <?php echo $comentario; ?> </div></td>
                    <td><div align="right"><?php echo "<b>".$fecha."</b>";?> <a  class="delete" id="<?=$ultimoid ?>"><i class="icon-remove"></i></a> </div></td>
                </tr>
            
           </table> 
        </div>

