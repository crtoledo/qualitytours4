<?php
class Solicitud extends ActiveRecord
{
    public function getsolicitud($page,$datoabuscar, $ppage=10)
    {
       return $this->paginate("id_usu=".$datoabuscar ,"page: $page", "per_page: $ppage");
    }
    
    Public function buscar_propia($dato)
    {
       return $this->find_by_sql("select * from solicitud where id_usu=".$dato);
    }
    
    Public function cancelar_suscripcion($dato)
    {
       return $this->find_by_sql("select * from solicitud where id_usu = " . $dato . " and activo_sol ='true'");
    }
}
?>
