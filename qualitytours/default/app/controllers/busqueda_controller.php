<?php
load::model('ubicacion');
load::model('usuario');

class BusquedaController extends AppController 
{
    
    public function before_filter() 
    {
        //Obtenemos el usuario si es que esta logueado
        if (Auth::is_valid())
        {
            $usuario = new Usuario();
            $usuario->find(Auth::get('id'));
            $this->username_usu = $usuario->username_usu;
            $this->id_usu = $usuario->id;
        }
        else
        {
            $this->username_usu = "NOREGISTRADO";
            $this->id_usu = 0;
        }
    }
    
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

