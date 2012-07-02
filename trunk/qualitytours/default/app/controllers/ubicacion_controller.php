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
                if (Input::hasPost('ubicacion'))
                {
                    $ubicacion = new Ubicacion(Input::post('ubicacion'));
                    if ($ubicacion->save())
                    {
                        Flash::success('Su ubicación fue registrada');
                        Input::delete();
                        Router::redirect("/");
                    }
                    else
                    {
                        Flash::info('Error en el ingreso de su ubicacion');
                        Router::redirect('/');
                    }
                }
                
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
