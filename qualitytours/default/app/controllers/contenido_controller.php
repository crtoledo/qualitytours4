<?php
load::model('publicacion');
load::model('contenido');

class ContenidoController extends AppController 
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
        
    
        
         public function ingresar($id) 
      {
  
         
      }
      
        
    
    public function imagen() {
       // View::select('index');  //para mostrar siempre la vista con los formularios
        if (Input::hasPost('oculto')) 
        {  //para saber si se envió el form
 
            //llamamos a la libreria y le pasamos el nombre del campo file del formulario
            //el segundo parametro de Upload::factory indica que estamos subiendo una imagen
            //por defecto la libreria Upload trabaja con archivos...
            $archivo = Upload::factory('imagen', 'image'); 
            $archivo->setExtensions(array('jpg', 'png', 'gif'));//le asignamos las extensiones a permitir
            if ($archivo->isUploaded()) 
            {
                if ($archivo->save())
                {
                    Flash::valid('Imagen subida correctamente...!!!');
                    Router::redirect('/');
                }
                else
                {
                    Flash::warning('No se ha Podido guardar la imagen...!!!');
                }
            }
            else
            {
                Flash::warning('No se ha Podido transmitir la imagen...!!!');
            }
        }
    }


  
    
  
    
    public function borrar()
    {
        if(Input::hasPost('contenido'))
        {
            
        }

    }
}

