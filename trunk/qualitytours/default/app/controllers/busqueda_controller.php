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
        if (Input::hasPost('string'))
        {
            $this->string = Input::post('string');
        }
        else
        {
            Router::redirect('/');
        }
    }
    
  
    
  
    
 
            
}

