<?php
class Solicitud extends ActiveRecord
{
    public function getsolicitud($page,$datoabuscar, $ppage=10)
    {
       return $this->paginate("id_usu=".$datoabuscar ,"page: $page", "per_page: $ppage");
    }
    
    Public function buscar_solicitud($dato)
    {
       return $this->find_by_sql("select * from solicitud where id_usu=".$dato. " and activo_sol ='true'");
    }

    Public function solicitud_aceptada($dato)
    {
       return $this->find_by_sql("select * from solicitud where id_usu=".$dato. " and activo_sol ='true' and estado_sol='Aceptada'");
    }

    Public function solicitud_renovacion($dato)
    {
       return $this->find_by_sql("select * from solicitud where id_usu=".$dato. " and activo_sol ='true' and tipo_sol='Renovacion'");
    }
    
    Public function confirmar_mail($dato)
    {
       return $this->find_by_sql("select * from solicitud where id_usu = " . $dato . " and activo_sol ='true'");
    }
    
}
?>
