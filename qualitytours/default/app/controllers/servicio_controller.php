<?php
load::model('servicio');
load::model('cliente');
class ServicioController extends AppController
{
    public function index()
    {
           
    }
    
    public function before_filter()
    {
           
    }
    
    public function ingresar()
    { 
          //verifica si se encuentra logueado
	if(!Auth::is_valid())
        {
                Flash::info('Debe iniciar sesión');
                Router::redirect("/");
        }       
        //verifica si el rol pertenece como corresponde
        else
        {
            if(Auth::get('rol_usu') != 'cliente')
            {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
            else
            {
                $cliente = new Cliente();
                $cliente->find(Auth::get('id'));
                $this->nombre_cliente = $cliente->nombre_cli;
                
                //Ingreso del servicio a la BD
            } 
        }   
        
        
        
        
    }
    
}
?>