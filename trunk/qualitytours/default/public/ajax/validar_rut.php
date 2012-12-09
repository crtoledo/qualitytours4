<?php
require_once 'connection.php';
$Db = new db();
$conn = $Db->open();

$tipo_rut = $_POST['tipo_rut'];
$rut = $_POST['rut'];

if ($tipo_rut == "privado")
{
    $resu = pg_query($conn,"select count(rut_usu) from cliente where rut_usu ='".$rut."' and estado_usu=TRUE");
}
if ($tipo_rut == "comercial")
{
    $resu = pg_query($conn,"select count(rut_cli) from cliente where rut_cli ='".$rut."' and estado_usu=TRUE");
}

$res = pg_fetch_all($resu);



if (is_array($res) && isset($res[0]))
{    
    if ($res[0]['count'] == 0)
    {
        echo "valido";
    }
    else if ($res[0]['count'] > 0)
    {
        echo "repetido";
    }
}
else
    return 'error';
$Db->close($conn);
?>
