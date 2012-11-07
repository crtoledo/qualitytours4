<?php
Load::model('comentario');
Load::model('publicacion');
Load::model('usuario');
Load::model('cliente');
    
    

class ComentarioController extends AppController 
{
    public function index()
    {
        
    }
    
    public function ingresar($id_proveniente,$leng)
    {
        
           
            if(Input::hasPost('comentario'))
            {
           
                $comentario = new Comentario(Input::post('comentario'));

                if(!$comentario->save())
                {
                    Flash::error('Error');
                }
                else
                {
                    if($id_proveniente != null)
                    {
                       if($leng == "es")
                       {
                           Router::redirect("cliente/detalle/".$id_proveniente.'/'.$leng);
                       }
                       else
                       {
                           Router::redirect("cliente/detalle/".$id_proveniente.'/'.$leng);
                       }
                        
                    }  
                    else
                    {
                        Flash::success('Comentario ingresado');
                        Router::redirect("/");   
                    }    
                    
                }

            }  
        
        
    }
    
    public function eliminar($id,$id_proveniente,$leng)
    {
         $comentario = new Comentario;
         
         $delete = $comentario->find($id);
         if($delete->sql("update Comentario set estado_com='f' where id=".$id))
         {
             if($id_proveniente != null)
                    {
                        if($leng == "es")
                        {
                           Router::redirect("cliente/detalle/".$id_proveniente.'/'.$leng);  
                        }
                        else
                        {
                           Router::redirect("cliente/detalle/".$id_proveniente.'/'.$leng);  
                        }
                       
                    }
                    else
                    {
                         Router::redirect("/");  
                    }
             Flash::success("comentario eliminado");
          
         }
         
             
        
    }        
    
}
