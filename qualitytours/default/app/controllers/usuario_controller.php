<?php
load::model('usuario');
load::model('cliente');

class UsuarioController extends AppController 
{
    
   public function before_filter() {
       //ayuda a que no salga error cuando se ingresa al formulario de busqueda por primera vez
       //con esto no sale error de variable no definida
       $this->tabla_activada='0'; 
       //0= nadie, 1= turista, 2= cliente
       $this->diferenciador= '0';
   }


   public function index()
    {
        
    }

    public function ingresar()
        { 
        if(Input::hasPost('usuario'))
            {
           
            $usuario = new Usuario(Input::post('usuario'));
            
            if(!$usuario->save()){
                Flash::error('Error al agregar Usuario');
            }
            else
            {
                Flash::success('Usuario ingresado satisfactoriamente');
                Router::redirect("/");
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
    
    
    public function buscar($page=1, $paginador_activado=1,$datoguardado=1,$opcion='nada'){
        if(Auth::is_valid())
        {
          if(Auth::get('rol_usu')== 'administrador')
           {
            if(input::hasPost('usuario'))
            {
                //se activa la tabla seccion resultados
                $this->tabla_activada='1';
                //se obtiene el tipo de eleccion
                $opciondebusqueda = input::post('eleccion');
                //se obtiene lo que se quiere buscar
                $palabraclave =input::post('usuario');
                
                //si la opcion es turista
                if($opciondebusqueda=='turista')
                {
                    $obtenerdatos = new Usuario();
                    $this->listaobtenida = $obtenerdatos->getdatosusuarios($page,$palabraclave);
                    //campos para paginar
                    $this->datoabuscarr = $palabraclave;
                    $this->opcionn = $opciondebusqueda;
                }
                //si la opcion es cliente
                else if ($opciondebusqueda=='cliente')
                {
                    $obtenerdatos = new Usuario();
                    $this->listaobtenida = $obtenerdatos->getdatosclientes($page,$palabraclave);
                    //campos para paginar
                    $this->datoabuscarr = $palabraclave;
                    $this->opcionn = $opciondebusqueda;                    
                }
                //si la opcion es turistas y clientes
                else if ($opciondebusqueda=='ambos')
                {
                    $obtenerdatos = new Usuario();
                    $this->listaobtenida = $obtenerdatos->getdatosambos($page,$palabraclave);
                    //campos para paginar
                    $this->datoabuscarr = $palabraclave;
                    $this->opcionn = $opciondebusqueda;                      
                }
                // funcionara cuando se active la paginacion
                   else if($paginador_activado == 2 )
                    {
                       $this->opcionn = $opcion;
                       if ($opcion == 'turista')
                       {
                            $obtenerdatos = new Usuario;
                            $this->listaobtenida = $obtenerdatos->getdatosusuarios($page,$datoguardado);
                            $this->datoabuscarr = $datoguardado;
                            $this->opcionn = $opcion;   
                       }
                       else if ($opcion=='cliente')
                       {
                            $obtenerdatos = new Usuario;
                            $this->listaobtenida = $obtenerdatos->getdatosclientes($page,$datoguardado);
                            $this->datoabuscarr = $datoguardado;
                            $this->opcionn = $opcion;                      
                       }
                       else if ($opcion =='ambos')
                       {
                            $obtenerdatos = new Usuario;
                            $this->listaobtenida = $obtenerdatos->getdatosambos($page,$datoguardado); 
                            $this->datoabuscarr = $datoguardado;
                            $this->opcionn = $opcion;
                       }    
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
                    
                    if($len == 2)
                    {
                        Flash::Success("Usuario logueado");
                        Router::redirect("/");
                    }
                   if($len == 1)
                    {
                        Flash::Success("Users logged in");
                        Router::redirect("en/index/1");
                    }
                    
                    
                }
                
            } 
            else 
            {
                if($len == 2)
                    {
                     $this->captura = 1; 
                      Flash::error("Fallo en la autenticación: Nombre de usuario o contraseña incorrecto");
                    }
                   if($len == 1)
                    {
                       $this->captura = 2; 
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
        
        if($leng == 2)
        {
          Auth::destroy_identity();
          Flash::info("Sesión cerrada");
          Router::redirect("/");  
        }
        else
        {
          Auth::destroy_identity();
          Flash::info("Closed Session");
          Router::redirect("en/index/1");   
        }
        
        
    }
    
    public function borrar()
    {
        if(Input::hasPost('usuario'))
        {
            
        }

    }
}
