<?php
load::model('usuario');

class UsuarioController extends AppController 
{
    
   public function before_filter() {
       //ayuda a que no salga error cuando se ingresa al formulario de busqueda por primera vez
       //con esto no sale error de variable no definida
       $this->tabla_activada='0'; 
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
        
        
    public function modificar(){
        
    }
    
    
    public function buscar($page=1, $paginador_activado=1,$datoguardado=1,$opcion='nada'){
        if(Auth::is_valid())
        {
          if(Auth::get('rol_usu')== 'administrador')
           {
            if(input::hasPost('usuario'))
            {
                $this->tabla_activada='1';
                $opciondebusqueda = input::post('eleccion');
                $palabraclave =input::post('usuario');
                
                if($opciondebusqueda=='turista')
                {
                    $obtenerdatos = new Usuario();
                    $this->listaobtenida = $obtenerdatos->getdatosusuarios($page,$palabraclave);
                    //campos para paginar
                    $this->datoabuscarr = $palabraclave;
                    $this->opcionn = $opciondebusqueda;
                }
                else if ($opciondebusqueda=='cliente')
                {
                    $obtenerdatos = new Usuario();
                    $this->listaobtenida = $obtenerdatos->getdatosclientes($page,$palabraclave);
                    //campos para paginar
                    $this->datoabuscarr = $palabraclave;
                    $this->opcionn = $opciondebusqueda;                    
                }
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
                Router::redirect("/");
            } 
        }
        else
        {
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
                $algo = $id;
                $usuarioaeliminar = new Usuario;
                $usuario = $usuarioaeliminar->find($id);
                $usuario->estado_usu = 'False';

                if ($usuario->update())
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
    public function autenticar() 
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
                Router::redirect("/usuario/admin");
               }
                else{
                    
                
                Flash::Success("Usuario logueado");
                Router::redirect("/");
                }
                
            } 
            else 
            {
                Flash::error("Fallo en la autenticación: Nombre de usuario o contraseña incorrecto");
            }        
         }
         else
         {
             Flash::info("Ingrese sus datos de login");
         }     
     }
     
     public function cerrarsesion()
    {
        Auth::destroy_identity();
        Flash::info("Sesión cerrada");
        Router::redirect("/");
        
    }
    
    public function borrar()
    {
        if(Input::hasPost('usuario'))
        {
            
        }

    }
}
