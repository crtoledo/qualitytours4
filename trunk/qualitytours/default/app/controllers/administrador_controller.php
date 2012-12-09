<?php

load::model('administrador');
load::model('usuario');
load::model('cliente');

class AdministradorController extends AppController {

    public function index() {
        
    }

    public function ingresar($id, $leng) {
        if (Auth::is_valid()) {
            if (Auth::get('rol_usu') == 'administrador') {

                $this->leng = $leng;

                $administrador = new Administrador();
                $administrador_confirmar = new administrador();

                //Obtenemos el id del usuario que queremos convertir a administrador
                $id_usuario = $id;

                //Creamos la instancia
                $user = new usuario();

                if (!$administrador_confirmar->buscar_adm_eliminado($id)) {
                    //Obtenemos los datos del usuario mediante su id
                    $datos_user = $user->find($id_usuario);
                    if ($datos_user->rol_usu != "administrador" && $datos_user->rol_usu != "cliente") {
                        //Paso de datos desde usuario encontrado a administrador a ingresar
                        $id = $datos_user->id;
                        $username_usu = $datos_user->username_usu;
                        $password_usu = $datos_user->password_usu;
                        $rol_usu = "administrador";
                        $nombre_usu = $datos_user->nombre_usu;
                        $apellido_usu = $datos_user->apellido_usu;
                        ///$rut_usu = $datos_user->rut_usu;
                        $email_usu = $datos_user->email_usu;
                        $estado_usu = $datos_user->estado_usu;

                        if (($administrador->sql("insert into Administrador  values(" . $id . ",0,'" . $username_usu . "','" . $password_usu . "','" . $rol_usu . "','" . $nombre_usu . "','" . $apellido_usu . "','" . $email_usu . "','" . $estado_usu . "');")) && ( $user->sql("update Usuario set rol_usu='administrador' where id=" . $id))) {
                            Flash::success($username_usu . " ahora es administrador");
                            Router::redirect("usuario/buscar/" . $leng);
                        } else {
                            Flash::error("Error en el ingreso del administrador");
                            Router::redirect("usuario/buscar/" . $leng);
                        }
                    } else {
                        Flash::error($datos_user->username_usu . " no se puede convertir en administrador");
                        Router::redirect("usuario/buscar/" . $leng);
                    }
                } else {
                    $user->find($id_usuario);
                    $user->rol_usu = "administrador";
                    $administrador_confirmar->estado_usu = "true";
                    if ($administrador_confirmar->update() && $user->update()) {
                        Flash::success($administrador_confirmar->username_usu . " ahora es administrador");
                        Router::redirect("usuario/buscar/" . $leng);
                    } else {
                        Flash::error($administrador_confirmar->username_usu . " no se puede convertir en administrador");
                        Router::redirect("usuario/buscar/" . $leng);
                    }
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::error("Pagina no encontrada");
            Router::redirect("/");
        }
    }

    public function notificaciones($tipo_consulta, $leng) {
        $this->leng = $leng;
        if (Auth::is_valid()) {
            if (Auth::get('rol_usu') == 'administrador') {
                if ($tipo_consulta == "pendiente") {
                    $this->codigo = 1;
                } else if ($tipo_consulta == "mail") {
                    $this->codigo = 2;
                } else if ($tipo_consulta == "modificacion") {
                    $this->codigo = 3;
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::error("Pagina no encontrada");
            Router::redirect("/");
        }
    }

//Lista todas las suscripciones para eliminar
    public function revocar($tipo_consulta, $leng) {
        $this->leng = $leng;
        if (Auth::is_valid()) {
            if (Auth::get('rol_usu') == 'administrador') {
                if ($tipo_consulta == "eliminacion") {
                    $this->codigo = 4;
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::error("Pagina no encontrada");
            Router::redirect("/");
        }
    }

    public function proceso_revocar($leng) {
        $this->leng = $leng;
        if (Auth::is_valid()) {
            if (Auth::get('rol_usu') == 'administrador') {
                //Se valida de que este marcado almenos un cliente
                if ($_REQUEST["clientes"] != " ") {
                    //Se obtienen la lista de id
                    $string_clientes_enviados = $_REQUEST["clientes"];

                    //Se crea el array con el id de los clientes
                    $array_clientes = explode(".", $string_clientes_enviados);

                    //Se le elimina el espacio al principio del array
                    unset($array_clientes[0]);

                    //Se obtiene la cantidad de elementos del array
                    $total_id = count($array_clientes);

                    $eliminacion_clientes = new cliente;

                    //Se crea variable que cuenta los update realizados
                    $cantidad = 0;
                    
                    //Se realiza los update a plan free
                    foreach ($array_clientes as &$id_cliente) {
                        $eliminacion_clientes->sql("Update cliente set tipo_plan='free' where id_usu=" . $id_cliente);
                        $cantidad++;
                    }

                    //Se valida de que la operacion de revocar suscripcion fue existosa
                    if ($total_id == $cantidad) {
                        Flash::info("Se ha revocado la suscripcion a ".$cantidad." clientes");
                        Router::redirect("/administrador/revocar/eliminacion/" . $leng);
                    } else {
                        Flash::error("No se ha revocado la suscripcion");
                        Router::redirect("/administrador/revocar/eliminacion/" . $leng);                        
                    }
                } else {
                    Flash::info("Seleccione un cliente");
                    Router::redirect("/administrador/revocar/eliminacion/" . $leng);
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::error("Pagina no encontrada");
            Router::redirect("/");
        }
    }

    public function eliminar($id, $leng) {
        if (Auth::is_valid()) {
            if (Auth::get('rol_usu') == 'administrador') {
                $this->leng = $leng;
                if ($id != 1) {
                    $administrador_eliminar = new administrador ();

                    //Verifico que exista el administrador
                    if ($administrador_eliminar->buscar_adm($id)) {
                        //obtengo los datos de la tabla usuario para actualizar el rol
                        $usuario_actualizar_rol = new usuario();
                        $usuario_actualizar_rol->find($id);

                        //actualizo el rol de administrador a turista
                        $usuario_actualizar_rol->rol_usu = "turista";
                        //elimina privilegios de la tabla administrador
                        $administrador_eliminar->estado_usu = "false";

                        if ($administrador_eliminar->update() && $usuario_actualizar_rol->update()) {
                            Flash::success("Administrador eliminado");
                            Router::redirect("usuario/buscar/" . $leng);
                        } else {
                            Flash::error("No se puede eliminar el administrador");
                            Router::redirect("usuario/buscar/" . $leng);
                        }
                    } else {
                        Flash::error("Datos incorrectos");
                        Router::redirect("usuario/buscar/" . $leng);
                    }
                } else {
                    Flash::error("Este administrador no se puede eliminar");
                    Router::redirect("usuario/buscar/" . $leng);
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::error("Pagina no encontrada");
            Router::redirect("/");
        }
    }

}
