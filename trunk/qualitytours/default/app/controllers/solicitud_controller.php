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
            $nueva_solicitud->fecha_sol = date("d-m-Y");
            $nueva_solicitud->estado_sol = "Pendiente";
            $nueva_solicitud->tipo_sol = "nueva";
            $nueva_solicitud->observaciones_sol = "No presenta observaciones";
            $nueva_solicitud->mail_sol = "false";
            $nueva_solicitud->modificaciones_sol = "false";
            $nueva_solicitud->activo_sol = "true";

            if ($nueva_solicitud->save()) {
                Flash::info('Solicitud ingresada correctamente');
                Router::redirect("/");
            } else {
                Flash::info('No se ingreso');
            }
        } else {
            Flash::info('No posee los privilegios necesarios');
            Router::redirect("/");
        }
    }

    public function ver($id) {
        $solicitud = new solicitud();
        //Se verifica que el turista sea el turista que consulta su solicitud
        if (Auth::get('id') == $id) {
            if ($solicitud->find_by_sql("select * from solicitud where id_usu = " . $id . " and activo_sol='true'")) {
                $this->fecha = $solicitud->fecha_sol;
                $this->estado = $solicitud->estado_sol;
                $this->tipo = $solicitud->tipo_sol;
                $this->observaciones = $solicitud->observaciones_sol;
                if ($solicitud->estado_sol == "Aprobada") {
                    $this->colortabla = "success";
                } else if ($solicitud->estado_sol == "Pendiente") {
                    $this->colortabla = "warning";
                } else if ($solicitud->estado_sol == "Rechazada" || $solicitud->estado_sol == "Cancelada") {
                    $this->colortabla = "error";
                } else if ($solicitud->estado_sol == "Esperando") {
                    $this->colortabla = "info";
                }
            } else {
                Router::redirect("/solicitud/vertodas/" . Auth::get("id"));
            }
        } else {
            Router::redirect("/");
        }
    }

    public function vertodas($id) {
        if (Auth::get('id') == $id) {
            $this->id_usuario_solicitud = $id;
        } else {
            Flash::error("Acceso denegado");
            Router::redirect("/");
        }
    }

    public function cancela($id) {
        if (Auth::get("id") == $id) {
            $cancelacion = new solicitud();
            $cancelacion->cancelar_suscripcion($id);
            $cancelacion->estado_sol = "Cancelada";
            $cancelacion->activo_sol = "false";
            if ($cancelacion->update()) {
                Flash::info('Ha cancelado su solicitud');
                Router::redirect("/");
            } else {
                Flash::info("error al cancelar la solicitud");
            }
        } else {
            Router::redirect("/");
        }
    }

}

?>
