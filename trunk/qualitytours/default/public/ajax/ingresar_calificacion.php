<?php
    require_once 'connection.php';
    $Db = new db();
    $conn = $Db->open();

    $votacion = $_POST['rating'];
    setlocale ( LC_TIME, 'spanish' );
    $fecha = strftime(date('Y/m/d'));

 
    $id_usu = $_POST['id_usu'];
    $id_cli = $_POST['id_cli'];
    
echo $id_usu;
    
        
 if($id_usu != $id_cli)
 {
        $validar = pg_num_rows(pg_query("select * from calificacion where id_usu=" . $id_usu."and cli_id_usu=".$id_cli));
    if ($validar == 0) {
        $res = pg_query("INSERT INTO calificacion(id_usu, cli_id_usu, valor_cal, fecha_cal)
        VALUES (" . $_POST['id_usu'] . "," . $_POST['id_cli'] . "," . $votacion . ",'" . $fecha . "')");
      
        
        
        if($votacion == "1")
        {
            echo "<div class='alert alert-success'>  <b>1</b> </div>";
        }
        if($votacion == "2")
        {
            echo "<div class='alert alert-success'> <b>2</b> </div>";
        }
        if($votacion == "3")
        {
            echo "<div class='alert alert-success'>  <b>3</b> </div>";
        }
        if($votacion == "4")
        {
            echo "<div class='alert alert-success'>  <b>4</b> </div>";
        }
        if($votacion == "5")
        {
            echo "<div class='alert alert-success'> <b>5</b> </div>";
        }
        
    }
    else
    {
        
        if($votacion == "1")
        {
            echo "<div class='alert alert-success'>  <b>1</b> </div>";
        }
        if($votacion == "2")
        {
            echo "<div class='alert alert-success'> <b>2</b> </div>";
        }
        if($votacion == "3")
        {
            echo "<div class='alert alert-success'>  <b>3</b> </div>";
        }
        if($votacion == "4")
        {
            echo "<div class='alert alert-success'>  <b>4</b> </div>";
        }
        if($votacion == "5")
        {
            echo "<div class='alert alert-success'> <b>5</b> </div>";
        }
        ?>
           
           <?php  $res2 = pg_query("UPDATE calificacion  set valor_cal=".$votacion." WHERE id_usu=". $_POST['id_usu']."and cli_id_usu=". $_POST['id_cli']); ?>
            
    <?php

    }
     
 }
 else 
 {
     echo "<div class='' style='width: 200px'><font size='2'> <b>Lo sentimos, no puede calificar su propio centro turístico</b> </font></div>";
 }

    $Db->close($conn);
?>