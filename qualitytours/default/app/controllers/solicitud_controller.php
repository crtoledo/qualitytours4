<?php

load::model('solicitud');

class SolicitudController extends AppController {

        public function before_filter() {
        if (!Auth::is_valid()) {
            Flash::info('Debe iniciar sesión');
            Router::redirect("/");
        }
    }
    
    public function ingresar($id) {
        if (Auth::is_valid()) {

            date_default_timezone_set('America/Santiago');
            $nueva_solicitud = new Solicitud();

            //se crean los valores
            $nueva_solicitud->id_usu = $id;
            //$nueva_solicitud-> = "";
            $nueva_solicitud->fecha_sol = date("d-m-Y");
            $nueva_solicitud->estado_sol = "pendiente";
            $nueva_solicitud->tipo_sol = "nueva";
            $nueva_solicitud->observaciones_sol = "No presenta observaciones";
            $nueva_solicitud->mail_sol = "false";
            $nueva_solicitud->modificaciones_sol = "false";

            if ($nueva_solicitud->save()) {
                Router::redirect("/");
            } else {
                Flash::info('No se ingreso');
            }
        } else {
            Flash::info('No posee los privilegios necesarios');
            Router::redirect("/");
        }
    }

    public function ver($id) 
    {
        $solicitud = new solicitud();
        //Se verifica que el turista sea el turista que consulta su solicitud
        if (Auth::get('id') == $id) 
        {
            if ($solicitud->find_by_sql("select * from solicitud where id_usu = " . $id . "and estado_sol='pendiente'")) 
            {
                $this->fecha = $solicitud->fecha_sol;
                $this->estado = $solicitud->estado_sol;
                $this->tipo = $solicitud->tipo_sol;
                $this->observaciones = $solicitud->observaciones_sol;
                if ($solicitud->estado_sol== "aprobada")
                {
                    $this->colortabla="success";
                }
                else if ($solicitud->estado_sol== "pendiente")
                {
                    $this->colortabla="warning";
                }
                else if ($solicitud->estado_sol== "rechazada")
                {
                    $this->colortabla="error";
                }
                else if ($solicitud->estado_sol== "mail")
                {
                    $this->colortabla="info";
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
    
    public function cancela($id)
    {
        
    }

}

?>
