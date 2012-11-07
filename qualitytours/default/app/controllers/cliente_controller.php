<?php
load::model('usuario');
load::model('cliente');
load::model('servicio');
load::model('ubicacion');
load::model('contenido');
load::model('comentario');

class ClienteController extends AppController 
{
     
    
    public function index()
    {
        
    }
    
    public function before_filter(){
        
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
                 if(Auth::get('rol_usu') != 'administrador' )
                {
                    Flash::info('No posee los privilegios necesarios');
		    Router::redirect("/");
                }
                else
                {
                    if(Input::hasPost('cliente'))
                    {

                        $cliente = new Cliente(Input::post('cliente'));
              
                        //Obtenemos el id del usuario que queremos convertir a cliente
                        $id_usuario = $cliente->id;

                        //Creamos la instancia
                        $user= new usuario();
                
                        //Obtenemos los datos del usuario mediante su id
                        $datos_user = $user->find($id_usuario);
                

                        //Paso de datos desde usuario encontrado a cliente a ingresar
                
                        $id= $datos_user->id;        
                        $username_usu = $datos_user->username_usu;
                        $password_usu = $datos_user->password_usu;
                        $rol_usu = 'cliente';
                        $nombre_usu = $datos_user->nombre_usu;
                        $apellido_usu = $datos_user->apellido_usu;
                        $rut_usu = $datos_user->rut_usu;
                        $email_usu = $datos_user->email_usu;
                        $estado_usu = $datos_user->estado_usu;
                        $nombre_cli = $cliente->nombre_cli;
                        $rut_cli = $cliente->rut_cli;
                        $giro = $cliente->giro_cli;
                        $telefono = $cliente->telefono_cli;
                        $visitas = 0;  
                        if($cliente->sql("insert into Cliente (id_usu,username_usu,password_usu,rol_usu,nombre_usu,apellido_usu,rut_usu,email_usu,estado_usu,nombre_cli,rut_cli,giro_cli,telefono_cli,visitas_cli) values(".$id.",'".$username_usu."','".$password_usu."','".$rol_usu."','".$nombre_usu."','".$apellido_usu."','".$rut_usu."','".$email_usu."','".$estado_usu."','".$nombre_cli."','".$rut_cli."','".$giro."','".$telefono."',".$visitas.");")&&($user->sql("update Usuario set rol_usu='cliente' where id=".$id)))
                        {
                            Flash::success("Cliente ingresado correctamente");
                            Input::delete();
                        }
                        else
                        {
                            Flash::error("Error en el ingreso del cliente");
                        }
                        //                Sentencia original
                        //                $cliente->sql("insert into Cliente (id_usu,username_usu,password_usu,rol_usu,nombre_usu,apellido_usu,rut_usu,email_usu,estado_usu,nombre_cli,rut_cli,giro_cli,telefono_cli,visitas_cli) values(".$id.",'".$username_usu."','".$password_usu."','".$rol_usu."','".$nombre_usu."','".$apellido_usu."','".$rut_usu."','".$email_usu."','".$estado_usu."','".$nombre_cli."','".$rut_cli."','".$giro."','".$telefono."',".$visitas.");");
                
                
                
                   }
   
                } 
            }    
                  
        
        
        
      } 
    
  
    
    public function borrar()
    {
        if(Input::hasPost('cliente'))
        {
            
        }

    }
    
    public function detalle($id,$leng)
    {
        
        $this->leng = $leng;
       
        
        if($leng == "en")
        {
            
            $this->idiom = "en";
            $client = new Cliente();
            $client = $client->find($id);
            $this->id_cliente = $id;

            //Comprueba que no se actualise el contador de visita si es el mismo dueño del centro turistico
            if(Session::get("id") == $id)
            {  
                 $captura = $client->visitas_cli ;
                 $actualiza = $captura+0;
                 $client->sql("update Cliente set visitas_cli=".$actualiza."where id_usu=".$id);
            }
            if(Session::get("id")!= $id)
            {
                $captura = $client->visitas_cli ;
                $actualiza = $captura+1;
                $client->sql("update Cliente set visitas_cli=".$actualiza."where id_usu=".$id);
            }
            if(Session::get("id") == null)
            {
                $captura = $client->visitas_cli ;
                $actualiza = $captura+1;
                $client->sql("update Cliente set visitas_cli=".$actualiza."where id_usu=".$id);
            }


            $this->nombre_cliente = $client->nombre_cli;
            $this->mostrar = $client->visitas_cli+1;
            $contenido = new Contenido();
            $contenido = $contenido->find("conditions: id_usu=".$id);
            $this->contenido = $contenido;


            $services = new Servicio();
            $services = $services->find_all_by('id_usu', $id);
            $this->array_servicios = $services;

            //2- Necesario para cargar el mapa
            $ubicacion = new Ubicacion();
            $ubicacion = $ubicacion->find_all_by('id_usu', $id);
            if($ubicacion != null)
            {
                $this->latitud = $ubicacion[0]->latitud_ubi; //El [0] debido a que nos entrega un array
                $this->longitud = $ubicacion[0]->longitud_ubi;
            }

            




                $comentario = new Comentario();
                //validar si existen comentarios
                if($comentario->count("conditions: estado_com='t' and cli_id_usu=".$id) == 0)
                {
                  $this->cont = 0;
                }
                if($comentario->count("conditions: estado_com='t' and cli_id_usu=".$id) > 0)
                {
                  $this->numComentarios = $comentario->count("conditions: estado_com='t' and cli_id_usu=".$id);  
                  $this->cont = 1;
                }
                $arr= $comentario->find("conditions: estado_com='t' and cli_id_usu=".$id,"order: id ASC");
                $contador = 0;
                $user = new Usuario();
                foreach ($arr as $comentario )
                {

                   $this->detalle[$contador] = $arr[$contador]->detalle_com;
                   $this->fecha[$contador] = $arr[$contador]->fecha_com;  
                   $nombre_usuario[$contador] = $user->find($arr[$contador]->id_usu);
                   $this->nombre[$contador] = $nombre_usuario[$contador]->nombre_usu;
                   $this->id[$contador] = $arr[$contador]->id_usu;
                   $this->idComentario[$contador] = $arr[$contador]->id;

                   $contador++;
                }
                $this->contador= $contador;
            
        }
        if($leng == "es")
        {
            
            $this->idiom = "es";
            $client = new Cliente();
            $client = $client->find($id);
            $this->id_cliente = $id;

            //Comprueba que no se actualise el contador de visita si es el mismo dueño del centro turistico
            if(Session::get("id") == $id)
            {  
                 $captura = $client->visitas_cli ;
                 $actualiza = $captura+0;
                 $client->sql("update Cliente set visitas_cli=".$actualiza."where id_usu=".$id);
            }
            if(Session::get("id")!= $id)
            {
                $captura = $client->visitas_cli ;
                $actualiza = $captura+1;
                $client->sql("update Cliente set visitas_cli=".$actualiza."where id_usu=".$id);
            }
            if(Session::get("id") == null)
            {
                $captura = $client->visitas_cli ;
                $actualiza = $captura+1;
                $client->sql("update Cliente set visitas_cli=".$actualiza."where id_usu=".$id);
            }


            $this->nombre_cliente = $client->nombre_cli;
            $this->mostrar = $client->visitas_cli+1;
            $contenido = new Contenido();
            $contenido = $contenido->find("conditions: id_usu=".$id);
            $this->contenido = $contenido;


            $services = new Servicio();
            $services = $services->find_all_by('id_usu', $id);
            if($services != null)
            {
              $this->array_servicios = $services;
            }
            else
            {
              $this->array_servicios = null;
            }
       

            //2- Necesario para cargar el mapa
            $ubicacion = new Ubicacion();
            $ubicacion = $ubicacion->find_all_by('id_usu', $id);
            if($ubicacion != null)
            {
                $this->latitud = $ubicacion[0]->latitud_ubi; //El [0] debido a que nos entrega un array
                $this->longitud = $ubicacion[0]->longitud_ubi;
            }
            else
            {
                $this->latitud = null;
                $this->longitud = null;
            }





                $comentario = new Comentario();
                //validar si existen comentarios
                if($comentario->count("conditions: estado_com='t' and cli_id_usu=".$id) == 0)
                {
                  $this->cont = 0;
                }
                if($comentario->count("conditions: estado_com='t' and cli_id_usu=".$id) > 0)
                {
                  $this->numComentarios = $comentario->count("conditions: estado_com='t' and cli_id_usu=".$id);  
                  $this->cont = 1;
                }
                $arr= $comentario->find("conditions: estado_com='t' and cli_id_usu=".$id,"order: id ASC");
                $contador = 0;
                $user = new Usuario();
                foreach ($arr as $comentario )
                {

                   $this->detalle[$contador] = $arr[$contador]->detalle_com;
                   $this->fecha[$contador] = $arr[$contador]->fecha_com;  
                   $nombre_usuario[$contador] = $user->find($arr[$contador]->id_usu);
                   $this->nombre[$contador] = $nombre_usuario[$contador]->nombre_usu;
                   $this->id[$contador] = $arr[$contador]->id_usu;
                   $this->idComentario[$contador] = $arr[$contador]->id;

                   $contador++;
                }
                $this->contador= $contador;
            
        }
        
        

        
       
        
       
    }
     
    
           
        
    
    
}

