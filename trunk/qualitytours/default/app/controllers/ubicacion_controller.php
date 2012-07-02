<?php
load::model('ubicacion');
load::model('cliente');

class UbicacionController extends AppController 
{
    public function index()
    {
        
    }
    
    public function ingresar()
    {
        if (Auth::is_valid())
        {
            if(Auth::get('rol_usu') == 'cliente')
            {
                $cliente = new Cliente();
                $cliente->find(Auth::get('id'));
                $this->nombre_cliente = $cliente->nombre_cli;
                $this->id_usu = Auth::get('id');
                
            }
            else //NO ES CLIENTE
            {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            } 
        }
        else //NO ESTA LOGUEADO
        {
            Flash::info('Debe iniciar sesión');
            Router::redirect("/");
        }
    }
}
