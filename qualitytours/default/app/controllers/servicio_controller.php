<?php
load::model('servicio');
load::model('cliente');
load::model('ubicacion');
load::model('comentario');
load::model('usuario');
class ServicioController extends AppController
{
    public function index()
    {
           
    }
    
    public function before_filter()
    {
           
    }
    
    public function detalle($id_servicio,$leng)
    {
            
             $this->leng = $leng;
            //Creamos objetos que necesitaremos
            $servicio = new Servicio();
            $ubicacion = new Ubicacion();
            $this->id_servicio = $id_servicio;
            

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
                
                //COMENTARIO
                $comentario = new Comentario();
                //validar si existen comentarios
                if ($comentario->count("conditions: estado_com='t' and id_ser=" .$id_servicio) == 0) {
                    $this->cont = 0;
                }
                if ($comentario->count("conditions: estado_com='t' and id_ser=" . $id_servicio) > 0) {
                    $this->numComentarios = $comentario->count("conditions: estado_com='t' and id_ser=" . $id_servicio);
                    $this->cont = 1;
                }
                $arr = $comentario->find("conditions: estado_com='t' and id_ser=" . $id_servicio, "order: id ASC");
                $contador = 0;
                $user = new Usuario();
                foreach ($arr as $comentario) {

                    $this->detalle2[$contador] = $arr[$contador]->detalle_com;
                    $this->fecha2[$contador] = $arr[$contador]->fecha_com;
                    $nombre_usuario[$contador] = $user->find($arr[$contador]->id_usu);
                    $this->nombre2[$contador] = $nombre_usuario[$contador]->nombre_usu;
                    $this->id2[$contador] = $arr[$contador]->id_usu;
                    $this->idComentario2[$contador] = $arr[$contador]->id;

                    $contador++;
                }
                $this->contador = $contador;
                
                //mandar nombre de usuario para el ajax.
                if(Auth::is_valid())
                {
                $id_usuario = Auth::get("id");
                $nombre = $user->find($id_usuario);
                $this->nombre_usuario = $nombre->username_usu;
                }
                
            }
            else //No existe servicio con ese id
            {
                Flash::info('No se puede encontrar el servicio indicado');
                Router::redirect("/");
            }
        
    }
    
    public function ingresar($leng)
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
                $this->leng = $leng;
                //Obtenemos el nombre del centro turístico
                $cliente = new Cliente();
                $cliente->find(Auth::get('id'));
                $this->nombre_cliente = $cliente->nombre_cli; 
                //SE CAPTURA EL TIPO DE PLAN:
                $tipoPlan = $cliente->tipo_plan;
                $servicio = new Servicio();
                
                if($tipoPlan == "free")
                {
                    $cantidad = $servicio->count("conditions: id_usu=".Auth::get("id"));
                    
                    if($cantidad > 1)
                    {
                        echo Flash::info("No puede publicar un servicio su plan superó el limite");
                        Router::redirect('/');
                    }
                    else
                    {
                         //SI HAY DATOS PARA INGRESAR SALVARLOS A LA BD ENTRA AL IF
                        if (Input::hasPost('servicio'))
                        {
                            $servicio = new Servicio(Input::post('servicio'));
                            
                            //SACAMOS LAS COMILLAS DEL TEXTO EN EL TEXTAREA (Detalle servicio)
                            $servicio->detalle_ser = str_replace("'", '', $servicio->detalle_ser);
                            
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
                if($tipoPlan == "normal")
                {
                    $cantidad = $servicio->count("conditions: id_usu=".Auth::get("id"));
                    if($cantidad > 3)
                    {
                        echo Flash::info("No puede publicar un servicio su plan superó el limite");
                        Router::redirect('/');
                    }
                    else
                    {
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
                if($tipoPlan == "plus")
                {
                    $cantidad = $servicio->count("conditions: id_usu=".Auth::get("id"));
                    
                    if($cantidad > 14)
                    {
                        echo Flash::info("No puede publicar un servicio su plan superó el limite");
                        Router::redirect('/');
                    }
                    else
                    {
                         //SI HAY DATOS PARA INGRESAR SALVARLOS A LA BD ENTRA AL IF
                       
                        if (Input::hasPost('servicio'))
                        {
                            $servicio = new Servicio(Input::post('servicio'));
                            
                             $nombre = $servicio->nombre_ser;
                             $detalle = $servicio->detalle_ser;
                             $precio  = $servicio->precio_ser;
                             $tipoes  = $servicio->tipo_ser;
                             $estado = $servicio->estado_ser;
                             $visitas = $servicio->visitas_ser;
                             $id_usu = $servicio->id_usu;
                             
                            if($tipoes == "Cabaña-motel")
                            {
                                $tipoen = "cabin-motel";
                            }
                            if($tipoes == "Departamentos")
                            {
                                $tipoen = "departments";
                            }
                            if($tipoes == "Estancia")
                            {
                                $tipoen = "residence";
                            }
                            if($tipoes == "Hospedaje Rural")
                            {
                                $tipoen="rural lodging";
                            }
                            if($tipoes == "Hostal")
                            {
                                $tipoen = "hostel";
                            }
                            if($tipoes == "Hosteria")
                            {
                                $tipoen = "hosteria";
                            }
                            if($tipoes == "Termas")
                            {
                                $tipoen = "hot spring";
                            }
                            if($tipoes == "Otro")
                            {
                                $tipoen = "other";
                            }
                            if($tipoes == "Hostel" || $tipoes == "Bed & Breakfast" || $tipoes == "Apart hotel"|| $tipoes=="Camping"|| $tipoes == "Lodge" || $tipoes == "Resort")
                            {
                                $tipoen = $tipoes;
                            }
                            
                            if($servicio->sql("insert into servicio (id_usu, precio_ser, detalle_ser, nombre_ser,estado_ser,visitas_ser,tipo_ser,tipo_ser_eng) VALUES (".$id_usu.",'".$precio."','".$detalle."','".$nombre."','".$estado."',".$visitas.",'".$tipoes."','".$tipoen."')"))
                            {
                                Flash::success('Servicio publicado satisfactoriamente');
                                Input::delete();
                                Router::redirect('/');
                                
                            }
                            else
                            {
                                echo flash::error("error");
                            }
//                            if($servicio->save())
//                            {
//                                Flash::success('Servicio publicado satisfactoriamente');
//                                Input::delete();
//                                Router::redirect('/');
//                            }
//                            else
//                            {
//                                Flash::info('Error al publicar el servicio');
//                            }

                        }           

                        //Ingreso del servicio a la BD
                    }
                        
                    
                }
                
                
                
                   
            } 
        }   
        
        
        
        
    }
    
}
?>