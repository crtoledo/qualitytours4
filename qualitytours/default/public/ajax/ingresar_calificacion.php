<?php
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

$votacion = $_POST['rating'];

$Db->close($conn);

 echo $votacion; 
 
 
?>