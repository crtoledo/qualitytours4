<?php
load::model('administrador');
load::model('usuario');

class AdministradorController extends AppController {
     
    
    public function index(){
        
      
             
        
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
        
                         
                            
         if(Input::hasPost('administrador'))
            {

                $administrador = new Administrador(Input::post('administrador'));
              
                //Obtenemos el id del usuario que queremos convertir a administrador
                $id_usuario = $administrador->id;

                //Creamos la instancia
                $user= new usuario();
                
                //Obtenemos los datos del usuario mediante su id
                $datos_user = $user->find($id_usuario);
                

                //Paso de datos desde usuario encontrado a cliente a ingresar
                
                $id= $datos_user->id;        
                $username_usu = $datos_user->username_usu;
                $password_usu = $datos_user->password_usu;
                $rol_usu = "administrador";
                $nombre_usu = $datos_user->nombre_usu;
                $apellido_usu = $datos_user->apellido_usu;
                $rut_usu = $datos_user->rut_usu;
                $email_usu = $datos_user->email_usu;
                $estado_usu = $datos_user->estado_usu;
                
                
                //actualizar el rol en la tabla usuario de turista a administrador:
                
                //$user->update();
                //$datos_user->update();
                //$datos_user->save();
                
               
                if(($administrador->sql("insert into Administrador  values(".$id.",'".$username_usu."','".$password_usu."','".$rol_usu."','".$nombre_usu."','".$apellido_usu."','".$rut_usu."','".$email_usu."','".$estado_usu."');"))&& ( $user->sql("update Usuario set rol_usu='administrador' where id=".$id)))
                {   
                    
                    Flash::success("Administrador ingresado correctamente");
                }
                else
                {
                    Flash::error("Error en el ingreso del administrador");
                }
//               
                
                
                
            }
      } 
    
        
        
    
        
        
        
   

}
