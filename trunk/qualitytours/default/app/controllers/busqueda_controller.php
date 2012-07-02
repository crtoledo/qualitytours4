<?php
load::model('ubicacion');

class BusquedaController extends AppController 
{
     
    
    public function index()
    {
        
    }
    
    public function buscar()
    {
        
    }
    public function buscando()
    {
        $this->busqueda = Input::post('ciudad');
        //$this->busqueda = $buscar;
       // Router::redirect("busqueda/buscando/".$busqueda);
    }
    
  
    
  
    
 
            
}

