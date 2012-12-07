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
    public function buscando($leng)
    {
        $this->leng = $leng;
        
        
        if (Input::hasRequest('string'))
        {
        
            $this->string = Input::request('string');
        }
        else
        {
            $this->string = "&nbsp;";
            //Router::redirect('/');
        }
    }
    
    public function buscando_tc($string, $leng) //FUNCION CREADA ESPECIALMENTE PARA EL TAGCLOUD
    {
        $this->leng = $leng;
        $this->string = $string;
    }
  
    
  
    
 
            
}

