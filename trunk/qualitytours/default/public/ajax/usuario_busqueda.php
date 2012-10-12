<?php
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();
$res = pg_query($conn,"select * from usuario where username_usu ilike '%".$_REQUEST['string']."%'");
$arr = pg_fetch_all($res);

if (is_array($arr) && isset($arr[0]))
{    
    echo json_encode($arr); 
}
else
    return null;
$Db->close($conn);
?>
