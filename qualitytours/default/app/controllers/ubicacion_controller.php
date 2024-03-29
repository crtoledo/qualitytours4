<?php
load::model('ubicacion');
load::model('cliente');

class UbicacionController extends AppController 
{
    public function index()
    {
        
    }
    
    public function ingresar($leng)
    {
        $this->leng = $leng;
        if (Auth::is_valid())
        {
            if(Auth::get('rol_usu') == 'cliente')
            {
                $cliente = new Cliente();
                $cliente->find(Auth::get('id'));
                $this->nombre_cliente = $cliente->nombre_cli;
                $this->id_usu = Auth::get('id');
                
                $v_ubi = new Ubicacion();
                if($v_ubi->count("conditions: id_usu=".Auth::get('id')." and estado_ubi=TRUE") == 0) //Verificamos que no exista ya la ubicacion
                {

                    if (Input::hasPost('ubicacion'))
                    {
                        $ubicacion = new Ubicacion(Input::post('ubicacion'));
                        
                        //ELIMINACION DE APOSTROFES
                        $ubicacion->region_ubi = str_replace("'", '', $ubicacion->region_ubi);
                        $ubicacion->ciudad_ubi = str_replace("'", '', $ubicacion->ciudad_ubi);
                        $ubicacion->direccion_ubi = str_replace("'", '', $ubicacion->direccion_ubi);
                        
                        if ($ubicacion->save())
                        {
                            //Actualizamos el id_ubi en cliente
                            $cliente->sql("update Cliente set id_ubi=(select id from Ubicacion where id_usu=".$this->id_usu." and estado_ubi=TRUE) where id_usu=".$this->id_usu);
                            
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
                else
                {
                    Flash::info('Su ubicación ya fue ingresada, si requiere modificarla comuniquese con un administrador');
                    Router::redirect("/");
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
    
    public function ingresar_mapa($leng)
    {
        if (Auth::is_valid())
        {
            if(Auth::get('rol_usu') == 'cliente')
            {
                $this->leng = $leng;
                $cliente = new Cliente();
                $cliente->find(Auth::get('id'));
                $this->nombre_cliente = $cliente->nombre_cli;
                $this->id_usu = Auth::get('id');
                
                $v_ubi = new Ubicacion();
                if($v_ubi->count("conditions: id_usu=".Auth::get('id')." and estado_ubi=TRUE") == 0) //Verificamos que no exista ya la ubicacion
                {

                    if (Input::hasPost('ubicacion'))
                    {
                        $ubicacion = new Ubicacion(Input::post('ubicacion'));
                        
                        //ELIMINACION DE APOSTROFES
                        $ubicacion->region_ubi = str_replace("'", '', $ubicacion->region_ubi);
                        $ubicacion->ciudad_ubi = str_replace("'", '', $ubicacion->ciudad_ubi);
                        $ubicacion->direccion_ubi = str_replace("'", '', $ubicacion->direccion_ubi);
                        
                        if ($ubicacion->save())
                        {
                            //Actualizamos el id_ubi en cliente
                            $cliente->sql("update Cliente set id_ubi=(select id from Ubicacion where id_usu=".$this->id_usu." and estado_ubi=TRUE) where id_usu=".$this->id_usu);
                            
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
                else
                {
                    Flash::info('Su ubicación ya fue ingresada, si requiere modificarla comuniquese con un administrador');
                    Router::redirect("/");
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
