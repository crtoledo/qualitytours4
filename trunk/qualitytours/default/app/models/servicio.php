<?php
class Servicio extends ActiveRecord
{
    public function initialize()
    { 
        $this->validates_presence_of('detalle_ser','message: Debe ingresar una descripción de su servicio');
        $this->validates_length_of("detalle_ser", $max=10000,$min=20);
    }

}
?>