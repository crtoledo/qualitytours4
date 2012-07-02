<?php
load::model('usuario');
load::model('cliente');

class ClienteController extends AppController 
{
     
    
    public function index()
    {
        
    }
    
    public function before_filter(){
        //verifica si se encuentra logueado
		if(!Auth::is_valid())
                {
                        Flash::info('Debe iniciar sesión');
			Router::redirect("/");
                        
		}
                
            //verifica si el rol pertenece como corresponde
            else
            {
                 if(Auth::get('rol_usu') != 'administrador')
                {
                    Flash::info('No posee los privilegios necesarios');
		    Router::redirect("/");
                }
                else
                {
                    
                } 
            }    
                  
	}

    public function ingresar()
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
                $rol_usu = $datos_user->rol_usu;
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
    
  
    
    public function borrar()
    {
        if(Input::hasPost('cliente'))
        {
            
        }

    }
}

