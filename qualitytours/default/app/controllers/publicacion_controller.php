<?php
load::model('publicacion');
load::model('contenido');

class PublicacionController extends AppController 
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
                 if(Auth::get('rol_usu') != 'administrador' && Auth::get('rol_usu') != 'cliente' )
                {
                    Flash::info('No posee los privilegios necesarios');
		    Router::redirect("/");
                }
                else
                {
                    
                } 
            }    
                  
	}
        
    public function editar($id,$leng)
    {
        //se captura el id para futuros usos
        $this->id_pub=$id;
        $this->leng= $leng;
    }

    public function ingresar()
        { 
                    
            if(Input::hasPost('publicacion'))
            {
           
                    $publicacion = new Publicacion(Input::post('publicacion'));
            
                    if($publicacion->save())
                 {
                   Flash::success('Publicación ingresado satisfactoriamente');
                   Input::delete();
                   Router::redirect('/');
                 }
                else
                 {
                     
                      Flash::error('Error al agregar Publicación');
                
                 }

            }
         
           
         } 
    
  
    
    public function eliminar($id)
    {
       $publicacion = new Publicacion();
       $contenido = new Contenido();
       $contenido->sql("update Contenido set estado_con='f' where id_pub=".$id);
       if($publicacion->sql("update Publicacion set estado_pub='f' where id=".$id))
       {
           
           Flash::success('Publicación eliminada');
	   Router::redirect("/");
       }
   
    }
    public function editarpub($id,$leng)
    {
    
       //se captura el id para futuros usos
        $this->id_pub=$id;
        $this->leng= $leng;
        
    }
    public function actualizar()
    {
        if(Input::hasPost('publicacion'))
            {
           
                    $publicacion = new Publicacion(Input::post('publicacion'));
            
                    $fecha = $publicacion->fecha_pub;
                    $titulo = $publicacion->titulo_pub;
                    $detalle = $publicacion->detalle_pub;
                    $id = $publicacion->id;
                    
                    if($publicacion->sql("update Publicacion set fecha_pub='".$fecha."' , titulo_pub='".$titulo."', detalle_pub='".$detalle."' where id=".$id))
                    {
                        Flash::success('Publicación actualizada');
			Router::redirect("/");
                    }
                    

            }
    }
            
}

