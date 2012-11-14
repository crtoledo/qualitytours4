<?php
load::model('solicitud');

class SolicitudController extends AppController 
{
    public function ingresar($id)
    {
        if(Auth::is_valid())
        {
            
            date_default_timezone_set('America/Santiago');
            $nueva_solicitud = new Solicitud();
            
            //se crean los valores
            $nueva_solicitud->id_usu = $id;
            //$nueva_solicitud-> = "";
            $nueva_solicitud->fecha_sol = date("d-m-Y");
            $nueva_solicitud->estado_sol = "pendiente";
            $nueva_solicitud->tipo_sol = "nueva";
            $nueva_solicitud->observaciones_sol ="No presenta observaciones";
            $nueva_solicitud->mail_sol = "false";
            $nueva_solicitud->modificaciones_sol = "false";
            
            if($nueva_solicitud->save())
            {
                Router::redirect("/");
            }
            else
            {
                Flash::info('No se ingreso');
            }
        }
        else
        {
            Flash::info('No posee los privilegios necesarios');
            Router::redirect("/");
        } 
    }
       
    
    public function ver($id)
    {
            
    }
}
?>
