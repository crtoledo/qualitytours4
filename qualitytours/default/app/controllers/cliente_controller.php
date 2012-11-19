<?php

load::model('usuario');
load::model('cliente');
load::model('servicio');
load::model('ubicacion');
load::model('contenido');
load::model('comentario');
load::model('solicitud');
load::model('calificacion');

class ClienteController extends AppController {

    public function index() {
        
    }

    public function ingresarsolicitud() {
        // se valida que este logeado el usuario
        if (!Auth::is_valid() || Auth::get("rol_usu")== "administrador") {
            Flash::info('No posee los privilegios necesarios');
            Router::redirect("/");
        } else {

            $id = Auth::get('id');
            $solicitud = new Solicitud ();

            //se verifica si el usuario tiene solicitudes
            if (!$solicitud->find("conditions: id_usu=" . Auth::get('id'))) {

                if (Input::hasPost('cliente')) {
                    $cliente = new Cliente(Input::post('cliente'));

                    //Obtenemos el id del usuario que queremos convertir a cliente
                    $id_usuario = $cliente->id;

                    //Creamos la instancia
                    $user = new usuario();

                    //Obtenemos los datos del usuario mediante su id
                    $datos_user = $user->find($id_usuario);

                    //Paso de datos desde usuario encontrado a cliente a ingresar
                    $id = $datos_user->id;
                    $username_usu = $datos_user->username_usu;
                    $password_usu = $datos_user->password_usu;
                    $rol_usu = 'turista';
                    $nombre_usu = $datos_user->nombre_usu;
                    $apellido_usu = $datos_user->apellido_usu;
                    $rut_usu = $datos_user->rut_usu;
                    $email_usu = $datos_user->email_usu;
                    $lenguaje_usu = $datos_user->lenguaje_usu;
                    ;
                    $estado_usu = "false";
                    $nombre_cli = $cliente->nombre_cli;
                    $rut_cli = $cliente->rut_cli;
                    $giro = $cliente->giro_cli;
                    $telefono = $cliente->telefono_cli;
                    $visitas = 0;
                    $plan = $cliente->tipo_plan;

                    if ($cliente->sql("insert into Cliente (id_usu,username_usu,password_usu,rol_usu,nombre_usu,apellido_usu,rut_usu,email_usu,estado_usu,lenguaje_usu,nombre_cli,rut_cli,giro_cli,telefono_cli,visitas_cli,tipo_plan) values(" . $id . ",'" . $username_usu . "','" . $password_usu . "','" . $rol_usu . "','" . $nombre_usu . "','" . $apellido_usu . "','" . $rut_usu . "','" . $email_usu . "','" . $estado_usu . "','" . $lenguaje_usu . "','" . $nombre_cli . "','" . $rut_cli . "','" . $giro . "','" . $telefono . "'," . $visitas . ",'" . $plan . "');")) {//&&($user->sql("update Usuario set rol_usu='cliente' where id=".$id)))
                        Flash::success("Solicitud enviada correctamente");
                        Input::delete();
                        Router::redirect("solicitud/ingresar/" . $id);
                    } else {
                        Flash::error("Error en el ingreso del cliente");
                    }
                }
            } else {
                // en caso de tener solicitudes, se revisa si estas estan activas
                if (!$solicitud->find("conditions: id_usu=" . Auth::get('id') . " and activo_sol='true' ")) {
                    // en caso de que no tenga solicitudes activas se actualiza el cliente en la tabla cliente
                    if (Input::hasPost('cliente')) {
                        $cliente = new Cliente(Input::post('cliente'));

                        $estado_usu = "false";
                        $nombre_cli = $cliente->nombre_cli;
                        $rut_cli = $cliente->rut_cli;
                        $giro = $cliente->giro_cli;
                        $telefono = $cliente->telefono_cli;
                        $visitas = 0;
                        $plan = $cliente->tipo_plan;

                        if ($cliente->sql("UPDATE cliente SET estado_usu='" . $estado_usu . "' , nombre_cli='" . $nombre_cli . "', rut_cli='" . $rut_cli . "', giro_cli='" . $giro . "', telefono_cli='" . $telefono . "', visitas_cli=" . $visitas . " , tipo_plan='" . $plan . "' WHERE id_usu =" . $id . ";")) {
                            Flash::success("Solicitud modificada correctamente");
                            Input::delete();
                            Router::redirect("solicitud/ingresar/" . $id);
                        }
                    }
                } else {
                    // en caso de que tenga una solicitud activa se pasa al controlador solicitud
                    Router::redirect("solicitud/ver/" . $id);
                }
            }
        }
    }

    public function borrar() {
        if (Input::hasPost('cliente')) {
            
        }
    }

    public function detalle($id, $leng) {

        $this->leng = $leng;

       
        $client = new Cliente();
        $client = $client->find($id);
        $this->id_cliente = $id;

        //Obtenemos la ubicacion del centro
        $ubicacion = new Ubicacion();
        $ubicacion = $ubicacion->find_by_sql("select *  from ubicacion where id_usu = " . $id);
        if ($ubicacion!=null)
        {
            $this->region_ubi = $ubicacion->region_ubi;
            $this->ciudad_ubi = $ubicacion->ciudad_ubi;
            $this->direccion_ubi = $ubicacion->direccion_ubi;
        }
        

        //Comprueba que no se actualise el contador de visita si es el mismo dueño del centro turistico
        if (Session::get("id") == $id) {
            $captura = $client->visitas_cli;
            $actualiza = $captura + 0;
            $client->sql("update Cliente set visitas_cli=" . $actualiza . "where id_usu=" . $id);
        }
        if (Session::get("id") != $id) {
            $captura = $client->visitas_cli;
            $actualiza = $captura + 1;
            $client->sql("update Cliente set visitas_cli=" . $actualiza . "where id_usu=" . $id);
        }
        if (Session::get("id") == null) {
            $captura = $client->visitas_cli;
            $actualiza = $captura + 1;
            $client->sql("update Cliente set visitas_cli=" . $actualiza . "where id_usu=" . $id);
        }


        $this->nombre_cliente = $client->nombre_cli;
        $this->mostrar = $client->visitas_cli + 1;
        $contenido = new Contenido();
        $contenido = $contenido->find("conditions: id_usu=" . $id);
        $this->contenido = $contenido;


        $services = new Servicio();
        $services = $services->find_all_by('id_usu', $id);
        $this->array_servicios = $services;

        //2- Necesario para cargar el mapa
        $ubicacion = new Ubicacion();
        $ubicacion = $ubicacion->find_all_by('id_usu', $id);
        if ($ubicacion != null) {
            $this->latitud = $ubicacion[0]->latitud_ubi; //El [0] debido a que nos entrega un array
            $this->longitud = $ubicacion[0]->longitud_ubi;
        }

        $comentario = new Comentario();
        //validar si existen comentarios
        if ($comentario->count("conditions: estado_com='t' and cli_id_usu=" . $id) == 0) {
            $this->cont = 0;
        }
        if ($comentario->count("conditions: estado_com='t' and cli_id_usu=" . $id) > 0) {
            $this->numComentarios = $comentario->count("conditions: estado_com='t' and cli_id_usu=" . $id);
            $this->cont = 1;
        }
        $arr = $comentario->find("conditions: estado_com='t' and cli_id_usu=" . $id, "order: id ASC");
        $contador = 0;
        $user = new Usuario();
        foreach ($arr as $comentario) {

            $this->detalle[$contador] = $arr[$contador]->detalle_com;
            $this->fecha[$contador] = $arr[$contador]->fecha_com;
            $nombre_usuario[$contador] = $user->find($arr[$contador]->id_usu);
            $this->nombre[$contador] = $nombre_usuario[$contador]->nombre_usu;
            $this->id[$contador] = $arr[$contador]->id_usu;
            $this->idComentario[$contador] = $arr[$contador]->id;

            $contador++;
        }
        $this->contador = $contador;
        //CALIFICACION
        $calificacion = new Calificacion();
        $result = $calificacion->count("conditions: cli_id_usu=".$id);


        if ($result != 0) {

            $promedio = $calificacion->average("valor_cal","conditions: cli_id_usu=".$id);
            $this->calificacion = $promedio;
            $this->cantidad = $result;
        } else {

            $this->calificacion = 0;
            $this->cantidad = $result;
        }
        
        //mandar nombre de usuario para el ajax.
        if(Auth::is_valid())
        {
           $id_usuario = Auth::get("id");
           $nombre = $user->find($id_usuario);
           $this->nombre_usuario = $nombre->username_usu;
        }
        
    }

    public function ver($id) {
        $vercliente = new cliente();

        if (Input::hasPost('cliente')) {

            $cliente = new Cliente(Input::post('cliente'));
            
            $solicitud_modificacion = new Solicitud ();
            $solicitud_modificacion = $solicitud_modificacion->modificar_solicitud($id);
            $solicitud_modificacion->modificaciones_sol = "true";

            //Paso de datos desde usuario encontrado a cliente a ingresar
            $username_usu = $cliente->username_usu;
            $password_usu = $cliente->password_usu;
            $nombre_usu = $cliente->nombre_usu;
            $apellido_usu = $cliente->apellido_usu;
            $rut_usu = $cliente->rut_usu;
            $email_usu = $cliente->email_usu;
            $nombre_cli = $cliente->nombre_cli;
            $rut_cli = $cliente->rut_cli;
            $giro = $cliente->giro_cli;
            $telefono = $cliente->telefono_cli;
            $plan = $cliente->tipo_plan;

            if ($cliente->sql("UPDATE cliente SET nombre_cli='" . $nombre_cli . "', rut_cli='" . $rut_cli . "', giro_cli='" . $giro . "', telefono_cli='" . $telefono . "', tipo_plan='" . $plan . "' WHERE id_usu =" . $id . ";") && $solicitud_modificacion->update()) {
                Flash::success("Solicitud modificada correctamente");
                Input::delete();
                Router::redirect("solicitud/ver/" . $id);
            } else {
                Flash::error("Error en el ingreso del cliente");
            }
        } else {
            $this->cliente = $vercliente->find($id);
            $this->qq = $vercliente->tipo_plan;
        }
    }

}

