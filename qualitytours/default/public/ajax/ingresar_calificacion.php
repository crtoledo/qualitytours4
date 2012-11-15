<?php
    require_once 'connection.php';
    $Db = new db();
    $conn = $Db->open();

    $votacion = $_POST['rating'];
    setlocale ( LC_TIME, 'spanish' );
    $fecha = strftime(date('Y/m/d'));

 
    $id_usu = $_POST['id_usu'];
    $id_cli = $_POST['id_cli'];
 
 if($id_usu != $id_cli)
 {
        $validar = pg_num_rows(pg_query("select * from calificacion where id_usu=" . $id_usu));

    if ($validar == 0) {
        $res = pg_query("INSERT INTO calificacion(id_usu, cli_id_usu, valor_cal, fecha_cal)
        VALUES (" . $_POST['id_usu'] . "," . $_POST['id_cli'] . "," . $votacion . ",'" . $fecha . "')");
    }
    else
    {
        ?>
            <script>
               alert("Lo sentimos no puede realizar una calificacion");
           </script>
    <?php

    }
     
 }
 else
 {?>
     <script>
        alert("Lo sentimos no puede realizar una calificacion");
      </script>
 <?php }
 
    $Db->close($conn);
?>