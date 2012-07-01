<?php
Load::model('comentario');
Load::model('publicacion');
Load::model('usuario');
    
    

class ComentarioController extends AppController 
{
    public function index()
    {
        
    }
    
    public function ingresar()
    {
            if(Input::hasPost('comentario'))
            {
           
            $comentario = new Comentario(Input::post('comentario'));
            
            if(!$comentario->save()){
                Flash::error('Error al agregar Usuario');
            }
            else
            {
                Flash::success('Comentario correctamente realizado');
                Router::redirect("/");
            }

            }  
        
        
    }
    public function eliminar($id)
    {
         $comentario = new Comentario;
         
         $delete = $comentario->find($id);
         if($delete->sql("update Comentario set estado_com='f' where id=".$id))
         {
             
             Flash::success("comentario eliminado");
             Router::redirect("/");
         }
         
             
        
    }        
    
}
