<?php
Load::model("categoria");
class CategoriaController extends AppController {
     
    
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
             $this->id_usu = Auth::get('id');
            if(Input::hasPost('categoria'))
            {
                
                $categoria = new Categoria(Input::post('categoria'));
                //Parametros del formulario:
               
                 
            
                if($categoria->save()){
                    Flash::success('Categoria ingresado satisfactoriamente');
                    Router::redirect("/");
                
                }
                else
                {
                Flash::error('Error al agregar Categoria');
                
                }

            }
        } 
}




?>