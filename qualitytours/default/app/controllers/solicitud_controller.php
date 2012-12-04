<?php

load::model('solicitud');
load::model('cliente');
load::model('usuario');
load::model('categoria');

class SolicitudController extends AppController {

    public function before_filter() {
        if (!Auth::is_valid()) {
            Flash::info('Debe iniciar sesión');
            Router::redirect("/");
        }
    }

///////////////////////////////////////////////////////////
//******************************************************//    
//**********    Funciones para el usuario    **********//
//****************************************************//    
///////////////////////////////////////////////////////      

    public function ingresar($id) {
        // se comprueba que sea turista el que ingresa la solicitud
        if (Auth::get('rol_usu') != "cliente") {

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
            Flash::error('Acceso denegado');
            Router::redirect("/");
        }
    }

    public function ver($id) {
        $solicitud = new solicitud();
        //Se verifica que el turista sea el turista que consulta su solicitud
        if (Auth::get('id') == $id) {
            // se valida que sea turista
            if (Auth::get('rol_usu') == "turista") {
                if ($solicitud->buscar_solicitud($id)) {
                    $this->fecha = $solicitud->fecha_sol;
                    $this->estado = $solicitud->estado_sol;
                    $this->tipo = $solicitud->tipo_sol;
                    $this->observaciones = $solicitud->observaciones_sol;
                    $this->mail_sol = $solicitud->mail_sol;

                    // Acontinuacion se le asigna el color a la tabla que se muestra en la vista
                    if ($solicitud->estado_sol == "Aceptada") {
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
                Flash::error('Acceso denegado');
                Router::redirect("/");
            }
        } else {
            Router::redirect("/");
        }
    }

    public function vertodas($id) {
        // se comprueba que el usuario quien consulta sea el mismo de las solicitudes
        if (Auth::get('id') == $id) {
            $this->id_usuario_solicitud = $id;
        } else {
            Flash::error("Acceso denegado");
            Router::redirect("/");
        }
    }

    public function cancela($id) {
        // se comprueba que el usuario quien cancela sea el mismo de la solicitud
        if (Auth::get("id") == $id) {
            $cancelacion = new solicitud();
            // se verifica que la solicitud exista
            if ($cancelacion->buscar_solicitud($id)) {
                // se valida que no pueda cancelar la solicitud si es que ya confirmo el envio del mail
                if ($cancelacion->mail_sol == "f") {
                    $cancelacion->estado_sol = "Cancelada";
                    $cancelacion->activo_sol = "false";

                    if ($cancelacion->update()) {
                        Flash::info('Ha cancelado su solicitud');
                        Router::redirect("/");
                    } else {
                        Flash::info("Error al cancelar la solicitud");
                    }
                } else {
                    Flash::info("No puede cancelar su solicitud");
                    Router::redirect("/");
                }
            } else {
                Flash::info("Solicitud no encontrada");
                Router::redirect("/");
            }
        } else {
            Router::redirect("/");
        }
    }


    Public function confirmacionmail_renovacion($id) {
        //se confirma que el que ingresa sea el usuario y no otro
        if (Auth::get("id") == $id) {
            $confirmacionmail = new solicitud();
            $confirmacionmail->confirmar_mail_renovacion($id);

            // Se verifica que no haya confirmado anteriormente
            if ($confirmacionmail->mail_sol != "t") {
                $confirmacionmail->mail_sol = "true";

                if ($confirmacionmail->update()) {
                    Flash::info('Ha confirmado el envio del mail');
                    Router::redirect("/cliente/administrarsuscripcion/");
                } else {
                    Flash::info("error al confirmar envio mail");
                }
            } else {
                Flash::info('Usted ya ha confirmado el envio del mail');
                //Router::redirect("/solicitud/ver/" . $id);
            }
        } else {
            //Router::redirect("/");
        }
    }

///////////////////////////////////////////////////////////
//******************************************************//    
//**********  Fin funciones para el usuario  **********//
//****************************************************//    
/////////////////////////////////////////////////////// 
//
///////////////////////////////////////////////////////////
//******************************************************//    
//************  funciones para el cliente  ************//
//****************************************************//    
/////////////////////////////////////////////////////// 

    public function renovarsuscripcion() {
        if (Auth::get("rol_usu") == "cliente") {
            $id_cliente = Auth::get("id");
            $solicitud_renovacion = new solicitud ();
            $cantidad_Renovacion = $solicitud_renovacion->count("conditions: id_usu=" . $id_cliente . "and activo_sol= true and estado_sol= 'Renovacion'");

            $this->ah = $cantidad_Renovacion;
            if ($cantidad_Renovacion == 0) {
                $cantidad_aceptada = $solicitud_renovacion->count("conditions: id_usu=" . $id_cliente . "and activo_sol= true and estado_sol= 'Aceptada'");
                if ($cantidad_aceptada == 1) {
                    //Se valida que se cumpla el requisito de un mes
                    date_default_timezone_set('America/Santiago');
                    $dia_actual = date("d");
                    $mes_actual = date("m");
                    $ano_actual = date("Y");

                    //Se obtiene la fecha del fin suscripcion
                    $datos_cliente = new cliente();
                    $datos_cliente->find_by_sql("select * from cliente where id_usu=" . $id_cliente);
                    $fecha_fin_suscripcion = $datos_cliente->fecha_fin_sus;

                    //Paso el dia, mes y año para poder comprarlos despues
                    list($ano, $mes, $dia) = explode('-', $fecha_fin_suscripcion);

                    if ($ano == $ano_actual && $mes == $mes_actual && $dia_actual <= $dia) {
                        // si se cumple el requisito se ingresa la solicitud de renovacion
                        $nueva_solicitud = new solicitud();

                        //se crean los valores
                        $nueva_solicitud->id_usu = $id_cliente;
                        $nueva_solicitud->fecha_sol = date("d-m-Y");
                        $nueva_solicitud->estado_sol = "Pendiente";
                        $nueva_solicitud->tipo_sol = "Renovacion";
                        $nueva_solicitud->observaciones_sol = "No presenta observaciones";
                        $nueva_solicitud->mail_sol = "false";
                        $nueva_solicitud->modificaciones_sol = "false";
                        $nueva_solicitud->activo_sol = "true";
                        if ($nueva_solicitud->save()) {
                            Flash::success('Solicitud ingresada correctamente');
                            Router::redirect("/cliente/administrarsuscripcion/");
                        } else {
                            Flash::error('Solicitud no ingresada');
                            Router::redirect("/");
                        }
                    } else {
                        Flash::info('No cumple los requisitos');
                        Router::redirect("/");
                    }
                } else {
                    Flash::notice('No tiene solicitudes aceptadas');
                    Router::redirect("/");
                }
            } else {
                Flash::error('No puede ingresar más de dos solicitudes de renovacion');
                Router::redirect("/cliente/administrarsuscripcion/");
            }
        } else {
            Flash::error('No posee los privilegios necesarios');
            Router::redirect("/");
        }
    }
    
    Public function confirmacionmail($id) {
        //se confirma que el que ingresa sea el usuario y no otro
        if (Auth::get("id") == $id) {
            $confirmacionmail = new solicitud();
            $confirmacionmail->confirmar_mail($id);

            // Se verifica que no haya confirmado anteriormente
            if ($confirmacionmail->mail_sol != "t") {
                $confirmacionmail->mail_sol = "true";

                if ($confirmacionmail->update()) {
                    Flash::info('Ha confirmado el envio del mail');
                    Router::redirect("/solicitud/ver/" . $id);
                } else {
                    Flash::info("error al confirmar envio mail");
                }
            } else {
                Flash::info('Usted ya ha confirmado el envio del mail');
                Router::redirect("/solicitud/ver/" . $id);
            }
        } else {
            Router::redirect("/");
        }
    }

///////////////////////////////////////////////////////////
//******************************************************//    
//**********  Fin funciones para el cliente  **********//
//****************************************************//    
/////////////////////////////////////////////////////// 
///////////////////////////////////////////////////////////
//******************************************************//    
//********** Funciones para el administrador **********//
//****************************************************//    
///////////////////////////////////////////////////////  

    Public function buscar() {
        
    }

    Public function administrar($solicitud, $usuario) {
        if (Auth::get("rol_usu") == "administrador") {

            $datos_solicitud = new solicitud();

            // valido que los datos obtenidos sean numericos
            if (is_numeric($solicitud) && is_numeric($usuario)) {
                // se valida que al solicitud corresponda al usuario
                if ($datos_solicitud->find_by_sql("select * from solicitud where id=" . $solicitud . " and id_usu = " . $usuario)) {

                    //Se pregunta si la solicitud fue modificada
                    if ($datos_solicitud->modificaciones_sol == "t") {
                        // So la solicitud tenia modificaciones, se pasa el campo de true a false
                        $datos_solicitud->modificaciones_sol = "false";
                        $datos_solicitud->update();
                    }

                    //Se obtienen los datos del usuario que quiere ser cliente
                    $datos_cliente = New Cliente;
                    $datos_cliente = $datos_cliente->find($usuario);

                    $this->id_usuario = $usuario;
                    $this->id_solicitud = $solicitud;
                    $this->nombre_cli = $datos_cliente->nombre_cli;
                    $this->nombre_usu = $datos_cliente->nombre_usu;
                    $this->rut_usu = $datos_cliente->rut_usu;
                    $this->rut_cli = $datos_cliente->rut_cli;
                    $this->nombre_usu = $datos_cliente->nombre_usu;
                    $this->giro_cli = $datos_cliente->giro_cli;
                    $this->telefono_cli = $datos_cliente->telefono_cli;
                    $this->tipo_plan = $datos_cliente->tipo_plan;

                    //datos de la solicitud para mostrar en la vista
                    $this->estado_sol = $datos_solicitud->estado_sol;
                    $this->observaciones = $datos_solicitud->observaciones_sol;
                    $this->fecha_sol = $datos_solicitud->fecha_sol;

                    //Se obtienen los datos de la solicitud para administrar los botones
                    $this->mail_sol = $datos_solicitud->mail_sol;

                    // se cargan los datos de la solicitud en especifico para observaciones
                    $this->solicitud = $datos_solicitud;
                } else {
                    Flash::info('Datos no corresponden a la solicitud');
                    Router::redirect("/solicitud/buscar");
                }
            } else {
                Flash::info('Datos no corresponden a la solicitud');
                Router::redirect("/solicitud/buscar");
            }
        } else {
            Flash::info('No posee los privilegios necesarios');
            Router::redirect("/");
        }
    }

    Public function ingresarobservacion($usuario, $solicitud) {
        if (Auth::get("rol_usu") == "administrador") {
            if (Input::hasPost('solicitud')) {

                $solicitud_observacion = new solicitud(Input::post('solicitud'));

                if ($solicitud_observacion->update()) {
                    Flash::notice("Observaciones ingresadas");
                    Router::redirect("/solicitud/administrar/" . $solicitud . "/" . $usuario);
                } else {
                    Flash::error("Error al ingresar observaciones");
                }
            } else {
                Flash::error("Acceso denegado");
                Router::redirect("/");
            }
        } else {
            Flash::info('No tiene los privilegios necesarios');
            Router::redirect("/");
        }
    }

    Public function aceptarsolicitud($id_cliente, $id_solicitud) {
        if (Auth::get("rol_usu") == "administrador") {
            // valido que los datos obtenidos sean numericos
            if (is_numeric($id_cliente) && ($id_solicitud)) {
                $comprobar_solicitud = new solicitud();
                // se valida que al solicitud corresponda al usuario
                if ($comprobar_solicitud->find_by_sql("select * from solicitud where id=" . $id_solicitud . " and id_usu = " . $id_cliente . "and activo_sol = 't'")) {

                    //Se obtiene el tipo de solicitud, si esta es de renovacion se debera buscar y actualizar 
                    //la anterior solicitud para cambiar el activo_sol a false
                    $tipo_solicitud = $comprobar_solicitud->tipo_sol;

                    date_default_timezone_set('America/Santiago');

                    $datos_actualizacion_cliente = new cliente;
                    $datos_actualizacion_usuario = new usuario;
                    $datos_actualizacion_solicitud = new solicitud;
                    $datos_actualizacion_categoria = new categoria;

                    //Se asignas las fechas de inicio y fin de la suscripcion
                    $fecha_inicio = date("d-m-Y");
                    $fecha_fin = date('d-m-Y', strtotime('+1 Year'));

                    //Se actualiza la tabla cliente con los nuevos datos del cliente
                    $sentencia = "UPDATE cliente SET id_sol=" . $id_solicitud . ", rol_usu='cliente', estado_usu= true, fecha_ini_sus='" . $fecha_inicio . "', fecha_fin_sus='" . $fecha_fin . "' WHERE id_usu=" . $id_cliente;
                    $sentencia_actualizar_rol = "UPDATE usuario SET id_sol=" . $id_solicitud . ", rol_usu='cliente' WHERE id=" . $id_cliente;
                    $sentencia_actualizar_solicitud = "UPDATE solicitud SET estado_sol='Aceptada' WHERE id=" . $id_solicitud;
                    $sentencia_actualizar_estado_categoria = "UPDATE categoria SET estado_cat=true WHERE id_usu =". $id_cliente;
                    if ($tipo_solicitud == "Renovacion") {
                        // Se busca la antigua solicitud
                        $solicitud_antigua = new solicitud;
                        $solicitud_antigua->solicitud_aceptada($id_cliente);

                        $solicitud_antigua->activo_sol = "false";
                        $solicitud_antigua->update();
                    }

                    if ($datos_actualizacion_cliente->sql($sentencia) && $datos_actualizacion_usuario->sql($sentencia_actualizar_rol) && $datos_actualizacion_solicitud->sql($sentencia_actualizar_solicitud) && $datos_actualizacion_categoria->sql($sentencia_actualizar_estado_categoria) ) {
                        // Si la aceptacion de la solicitud de renovacion fue exitosa,
                        //se procede a eliminar la antigua solicitud, solo si esta existe.

                        Flash::info('Solicitud aceptada, cliente ingresado');
                        Router::redirect("/solicitud/administrar/" . $id_solicitud . "/" . $id_cliente);
                    } else {
                        
                    }
                } else {
                    Flash::info('Datos no corresponden a la solicitud');
                    Router::redirect("/");
                }
            } else {
                Flash::info('Datos no corresponden a la solicitud');
                Router::redirect("/");
            }
        } else {
            Flash::info('No tiene los privilegios necesarios');
            Router::redirect("/");
        }
    }

    Public function rechazar($id, $usuario) {
        if (Auth::get("rol_usu") == "administrador") {
            // valido que los datos obtenidos sean numericos
            if (is_numeric($id) && is_numeric($usuario)) {
                $solicitud_rechazar = new solicitud ();
                if ($solicitud_rechazar->find($id)) {
                    $solicitud_rechazar->activo_sol = "false";
                    $solicitud_rechazar->estado_sol = "Rechazada";

                    if ($solicitud_rechazar->update()) {
                        Flash::info("Solicitud Rechazada");
                        Router::redirect("/solicitud/administrar/" . $id . "/" . $usuario);
                    } else {
                        Flash::error("Error al actualizar");
                    }
                } else {
                    Flash::error("Error al rechazar la solicitud");
                }
            } else {
                Flash::info('Datos no son correctos');
                Router::redirect("/");
            }
        } else {
            Flash::info('No tiene los privilegios necesarios');
            Router::redirect("/");
        }
    }

    public function aviso($id_solicitud, $id_usuario) {
        //Se verifica el rol
        if (Auth::get("rol_usu") == "administrador") {
            // se verifica que los datos enviados sean numericos
            if (is_numeric($id_solicitud) && is_numeric($id_usuario)) {
                $solicitud = new solicitud;
                // Se verifica que la solicitud exista y que ademas los datos (id) correspondan a ella
                if ($solicitud->find_by_sql("select * from solicitud where id=" . $id_solicitud . " and id_usu = " . $id_usuario . "and activo_sol='true'")) {

                    //Se cambian los valores de la solicitud
                    $solicitud->estado_sol = "Esperando";
                    if ($solicitud->update()) {
                        Router::redirect("/solicitud/administrar/" . $id_solicitud . "/" . $id_usuario);
                    } else {
                        Flash::error('Error al actualizar la solicitud');
                        Router::redirect("/solicitud/administrar/" . $id_solicitud . "/" . $id_usuario);
                    }
                } else {
                    Flash::info('Datos no corresponden a la solicitud');
                    Router::redirect("/");
                }
            } else {
                Flash::info('Datos no son correctos');
                Router::redirect("/");
            }
        } else {
            Flash::info('No tiene los privilegios necesarios');
            Router::redirect("/");
        }
    }

}

///////////////////////////////////////////////////////////
//******************************************************//    
//******** Fin funciones para el administrador ********//
//****************************************************//    
///////////////////////////////////////////////////////
?>