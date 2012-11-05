<?php
load::model('usuario');
load::model('cliente');

class UsuarioController extends AppController 
{
    
   public function before_filter() {

   }


   public function index()
    {
        
    }

    public function ingresar($leng)
        { 
        $this->leng= $leng;
        
        
        if(Input::hasPost('usuario'))
            {
           
            $usuario = new Usuario(Input::post('usuario'));
            
            if(!$usuario->save()){
                
                if($leng == "es")
                {
                    Flash::error('Error al agregar Usuario');
                }
                else
                {
                    Flash::error('user add error');
                }
                
            }
            else
            {
                if($leng == "es")
                {
                    Flash::success('Usuario ingresado satisfactoriamente');
                    Router::redirect("index");
                }
                else
                {
                    Flash::success('Add user successful');
                    Router::redirect("index/?l=en");
                }
                
            }

            }
        } 
        
        
    public function modificar($id){
     if(Auth::is_valid())
        {
      if(Auth::get('rol_usu')== 'administrador')
           {  
        $usuarioamodificar = new Usuario;
        $verificarrol = $usuarioamodificar->find($id);
        if($verificarrol->rol_usu=='turista')
        {
            if(Input::hasPost('usuario'))
            {
                $usuarioactualizado = new usuario;
                if($usuarioactualizado->update(Input::post('usuario')))
                {
                    Flash::info("Usuario ".$verificarrol->username_usu." modificado con exito");
                    Router::redirect("usuario/buscar");
                }
            }
            else
            {
                $this->diferenciador='1';
                $this->usuario = $verificarrol;
            }
        }
        else
        {
            $clienteamodificar = new cliente;
            if(Input::hasPost('cliente'))
            {
                $clienteactualizado = new Cliente;
                if($clienteactualizado->update(Input::post('cliente')))
                {
                    Flash::info("Usuario ".$verificarrol->username_usu." modificado con exito");
                    Router::redirect("usuario/buscar");
                }
                else
                {
                    Flash::error("No se modifico");
                }
            }
            else
            {             
                $this->diferenciador='2';
                $this->cliente = $clienteamodificar->find($id); 
            }
        }
      }
      else
        {
          Flash::info('No posee los privilegios necesarios');
          Router::redirect("/");
        } 
    }
     else
    {
        Flash::info('No posee los privilegios necesarios');
        Router::redirect("/");
    } 
}
    
    
    public function buscar($leng){
        $this->leng= $leng;
    }
    
    
    
    public function admin(){
        
    }

    
    public function eliminar($id){
       if(Auth::is_valid())
        {
           if(Auth::get('rol_usu')== 'administrador')
           {
                //problemas tabla cliente y usuario
                $usuarioaeliminar = new Usuario;
                $usuario = $usuarioaeliminar->find($id);
                $usuario->estado_usu = 'False';
                
                //se verfica si es cliente
                if($usuario->rol_usu == 'cliente')
                {
                    $clienteaeliminar = new Cliente;
                    $cliente = $clienteaeliminar->find($id);
                    $cliente->estado_usu = 'False';
                    // si es cliente se actualiza la tabla usuario y la tabla cliente
                   if ($usuario->update()&& $cliente->update())
                    {

                    }
                    else
                    {
                        Flash::error('No se puede eliminar');
                    }
                }
                //en caso de que solo es turista, se actualiza solamente la tabla usuario
                else if ($usuario->update())
                 {

                 }
                else
                 {
                    Flash::error('No se puede eliminar');
                 }
         }
           else
           {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
           }
        }
       else
        {
            Router::redirect("/");
        }
    }
            
    
    //Función para autenticar al usuario
    public function autenticar($len) 
    {
         
         if(Input::hasPost("username_usu","password_usu"))
         {
            //Flash::info("Datos recibidos.. se procede a autenticar");
             
          

            $user = Input::post('username_usu');
            $pass = Input::post('password_usu');

            $auth = new Auth("model", "class: usuario", "username_usu: $user", "password_usu: $pass");

            if ($auth->authenticate()) 
            {
               //captura el rol del usuario para futuros usos
                Session::set("rol_usu",Auth::get('rol_usu'));
                Session::set("id",Auth::get('id'));
               if(Session::get('rol_usu')=='administrador')
               {
                    
                        Flash::Success("Usuario logueado");
                        Router::redirect("/");
                    
                  
               }
                else{
                        $usuario = new Usuario();
                        $buscar = $usuario->find(Session::get("id"));
                        $idiom = $buscar->lenguaje_usu;
                    if($idiom == "f")
                    {   
                        
                            
                            Flash::Success("Usuario logueado");
                            Router::redirect("/");
                       
                    
                    }
                    else
                    {
                        
                            Flash::Success("Users logged in");
                            Router::redirect("index/en");
                       
                    }
                }
                
            } 
            else 
            {
                if($len == "es")
                    {
                     $this->captura = "es"; 
                      Flash::error("Fallo en la autenticación: Nombre de usuario o contraseña incorrecto");
                    }
                   if($len == "en")
                    {
                       $this->captura = "en"; 
                      Flash::error("Authentication failed: Username or password incorrect");
                    }
                
            }        
         }
         else
         {
             Flash::info("Ingrese sus datos de login");
         }     
     }
     
     public function cerrarsesion($leng)
    {
        
        if($leng == "es")
        {
          Auth::destroy_identity();
          Flash::info("Sesión cerrada");
          Router::redirect("index");  
        }
        else
        {
          Auth::destroy_identity();
          Flash::info("Closed Session");
          Router::redirect("index/?l=en");   
        }
        
        
    }
    
    public function borrar()
    {
        if(Input::hasPost('usuario'))
        {
            
        }

    }
    public function leng($leng)
    {
       
        
        $usuario = new Usuario();
        $buscar = $usuario->find(Session::get("id"));
        $idiom = $buscar->lenguaje_usu;
        
        if($idiom == "t")
        {
            $this->lenguaje_usu = "en";
            
        }
        else
        {
            $this->lenguaje_usu = "es"; 
        }
        
        $this->lengselect =  $leng;
        
        
    }
    public function leng2($leng)
    {
        $id = Session::get("id");
        if($leng == "es")
        {
          $usuario = new Usuario();
          $usuario->sql("UPDATE usuario set lenguaje_usu='f' WHERE id=".$id);
         flash::info("A cambiado su idioma predeterminado a español");
          router::redirect("/");
          
        }
        else
        {
          $usuario = new Usuario();
          $usuario->sql("UPDATE usuario set lenguaje_usu='t' WHERE id=".$id);
          flash::info("changed the default language to English");
          router::redirect("index/?l=en");
          
        }
    }
}
