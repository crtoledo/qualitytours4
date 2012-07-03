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
                 if(Auth::get('rol_usu') != 'administrador' && Auth::get('rol_usu') != 'cliente')
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
             //CAPTURA EL ID DELA PUBLICACION PARA ENVIARLA A LA VISTA
             $this->id_pub = $id;
         
      }
      
        
    //FUNCION PARA SUBIR CONTENIDO DEL ADMINISTRADOR
    public function admincont() {
       
        if (Input::hasPost('contenido')) 
        {  //para saber si se envió el form
 
            //llamamos a la libreria y le pasamos el nombre del campo file del formulario
            //el segundo parametro de Upload::factory indica que estamos subiendo una imagen
            //por defecto la libreria Upload trabaja con archivos...
            $archivo = Upload::factory('imagen', 'image'); 
            $archivo->setExtensions(array('jpg', 'png', 'gif'));//le asignamos las extensiones a permitir
            
            $contenido = new Contenido(Input::post('contenido'));
            //OBTIENE LOS PARAMETROS DEL FORMULARIO
            $id_pub = $contenido->id_pub;
            $fecha = $contenido->fecha_con;
            $nombre = $contenido->nombre_con;
            $tipo = $contenido->tipo_con;
      
            if ($archivo->isUploaded()) 
            {     //CAPTURA LA RUTA TRANSFOMADA EN MD5 PARA GUARDARLA EN LA BD
                if($ruta = $archivo->saveRandom())
                {   

                    Flash::valid('Imagen subida correctamente...!!!');
                    if($contenido->sql("insert into Contenido(id_pub, ruta_con, fecha_con, nombre_con, tipo_con,estado_con)values(".$id_pub.",'".$ruta."','".$fecha."','".$nombre."','".$tipo."','t')"))
                    {   
                     Flash::success('Contenido Ingresado Exitosamente.');
                     Router::redirect("/");
                    }       
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


    public function ingresarcli() {
       
        if (Input::hasPost('contenido')) 
        {  //para saber si se envió el form
 
            //llamamos a la libreria y le pasamos el nombre del campo file del formulario
            //el segundo parametro de Upload::factory indica que estamos subiendo una imagen
            //por defecto la libreria Upload trabaja con archivos...
            $archivo = Upload::factory('imagen', 'image'); 
            $archivo->setExtensions(array('jpg', 'png', 'gif'));//le asignamos las extensiones a permitir
            
            $contenido = new Contenido(Input::post('contenido'));
            //OBTIENE LOS PARAMETROS DEL FORMULARIO
            $id_usu = $contenido->id_usu;
            $fecha = $contenido->fecha_con;
            $nombre = $contenido->nombre_con;
            $tipo = $contenido->tipo_con;
      
            if ($archivo->isUploaded()) 
            {     //CAPTURA LA RUTA TRANSFOMADA EN MD5 PARA GUARDARLA EN LA BD
                if($ruta = $archivo->saveRandom())
                {   

                    Flash::valid('Imagen subida correctamente...!!!');
                    if($contenido->sql("insert into Contenido(id_usu, ruta_con, fecha_con, nombre_con, tipo_con,estado_con)values(".$id_usu.",'".$ruta."','".$fecha."','".$nombre."','".$tipo."','t')"))
                    {   
                     Flash::success('Contenido Ingresado Exitosamente.');
                     Router::redirect("/");
                    }       
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

