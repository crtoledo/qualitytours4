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
    public function buscando($buscar)
    {
        $this->busqueda = $buscar;
       // Router::redirect("busqueda/buscando/".$busqueda);
    }
    
  
    
  
    
 
            
}

