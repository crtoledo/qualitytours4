<?php
load::model('servicio');
load::model('cliente');
load::model('ubicacion');
class ServicioController extends AppController
{
    public function index()
    {
           
    }
    
    public function before_filter()
    {
           
    }
    
    public function detalle($id_servicio)
    {
        
            //Creamos objetos que necesitaremos
            $servicio = new Servicio();
            $ubicacion = new Ubicacion();
            

            if ($servicio->count("conditions: id=".$id_servicio) != 0)
            {
                $servicio->find($id_servicio);
                $this->nombre = $servicio->nombre_ser;
                $this->detalle = $servicio->detalle_ser;
                $this->precio = $servicio->precio_ser;

                //Obtenemos el id del cliente
                $id_cliente = $servicio->id_usu;
                
                //Obtenemos el nombre del centro turistico (cliente)
                $cli = new Cliente();
                $cli->find($id_cliente);
                $this->nombre_cliente = $cli->nombre_cli;
            
                //Obtenemos la ubicacion
                $ubicacion->find_by_sql("select *  from ubicacion where   id_usu = ".$id_cliente);
                $this->latitud  = $ubicacion->latitud_ubi;
                $this->longitud = $ubicacion->longitud_ubi;
                $this->direccion= $ubicacion->direccion_ubi;
                $this->ciudad   = $ubicacion->ciudad_ubi;
                $this->region   = $ubicacion->region_ubi;
            }
            else //No existe servicio con ese id
            {
                Flash::info('No se puede encontrar el servicio indicado');
                Router::redirect("/");
            }
        
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
            //POSEE LOS PRIVILEGIOS
            else
            {
                //Obtenemos el nombre del centro turístico
                $cliente = new Cliente();
                $cliente->find(Auth::get('id'));
                $this->nombre_cliente = $cliente->nombre_cli; 
                
                //SI HAY DATOS PARA INGRESAR SALVARLOS A LA BD ENTRA AL IF
                if (Input::hasPost('servicio'))
                {
                    $servicio = new Servicio(Input::post('servicio'));
            
                    if($servicio->save())
                    {
                    Flash::success('Servicio publicado satisfactoriamente');
                    Input::delete();
                    Router::redirect('/');
                    }
                    else
                    {
                        Flash::info('Error al publicar el servicio');
                    }
                
                }           
                
                //Ingreso del servicio a la BD
            } 
        }   
        
        
        
        
    }
    
}
?>