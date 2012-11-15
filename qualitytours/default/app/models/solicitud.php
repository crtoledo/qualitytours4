<?php
class Solicitud extends ActiveRecord
{
    public function getsolicitud($page,$datoabuscar, $ppage=10)
    {
       return $this->paginate("id_usu=".$datoabuscar ,"page: $page", "per_page: $ppage");
    }
}
?>
