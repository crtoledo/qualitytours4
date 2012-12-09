<?php
class Cliente extends ActiveRecord
{
    public function initialize()
    {
        
        
    }
    
    public function before_create()
    {
       //valida que solo exista un rut
      if($this->exists("rut_cli='".$this->rut_cli."'"))
      { 
          Flash::error('El rut comercial ingresado ya se encuentra en nuestros registros.'); 
           return 'cancel'; 
      }
      if($this->exists("rut_usu='".$this->rut_usu."'"))
      { 
          Flash::error('El rut privado ingresado ya se encuentra en nuestros registros.'); 
           return 'cancel'; 
      }
    }
    
    public function buscar_cliente($dato)
    {
        return $this->find_by_sql("select * from cliente where id_usu=".$dato. " and estado_usu = true");
    }
}
?>


