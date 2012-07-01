<?php
load::model('usuario');

class UsuarioController extends AppController 
{
     
    
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
    
    
    public function buscar(){
        
    }
    
    
    
    public function admin(){
        
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
