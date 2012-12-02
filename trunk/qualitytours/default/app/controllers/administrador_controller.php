<?php

load::model('administrador');
load::model('usuario');

class AdministradorController extends AppController {

    public function index() {
        
    }

    public function before_filter() {
        //verifica si se encuentra logueado
        if (!Auth::is_valid()) {
            Flash::info('Debe iniciar sesión');
            Router::redirect("/");
        }
        //verifica si el rol pertenece como corresponde
        else {
            if (Auth::get('rol_usu') != 'administrador') {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        }
    }

    public function ingresar($id, $leng) {
        $this->leng = $leng;

        $administrador = new Administrador();

        //Obtenemos el id del usuario que queremos convertir a administrador
        $id_usuario = $id;

        //Creamos la instancia
        $user = new usuario();

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
    }

    public function notificaciones($tipo_consulta) {
        if($tipo_consulta == "pendiente") {
            $this->codigo = 1;
        } else if($tipo_consulta == "mail") {
            $this->codigo = 2;
        } else if ($tipo_consulta == "modificacion") {
            $this->codigo = 3;
        }
    }

}
