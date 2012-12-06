<?php
class Administrador extends ActiveRecord
{
    public function initialize()
    {  
    }
    Public function buscar_adm($dato)
    {
       return $this->find_by_sql("select * from administrador where id_usu=".$dato. " and estado_usu ='true'");
    }
        Public function buscar_adm_eliminado($dato)
    {
       return $this->find_by_sql("select * from administrador where id_usu=".$dato. " and estado_usu ='false'");
    }
}
?>


