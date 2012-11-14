<?php
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

$votacion = $_POST['rating'];

$Db->close($conn);

 echo "Calificación: ".$votacion; 
 echo "<br>ID cliente: ".$_POST['id_cli'];
 echo "<br>ID usuario q califica: ".$_POST['id_usu'];
 
 
?>