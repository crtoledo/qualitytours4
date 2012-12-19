<?php

load::model('usuario');
load::model('cliente');
load::model('servicio');
load::model('ubicacion');
load::model('contenido');
load::model('comentario');
load::model('solicitud');
load::model('calificacion');
load::model('categoria');

class ClienteController extends AppController {

    public function index() {
        
    }

    public function ingresarsolicitud() {
        // se valida que este logeado el usuario
        if (!Auth::is_valid() || Auth::get("rol_usu") == "administrador") {
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
                    //$rut_usu = $datos_user->rut_usu;
                    $email_usu = $datos_user->email_usu;
                    $lenguaje_usu = $datos_user->lenguaje_usu;
                    $estado_usu = "false";
                    $nombre_cli = $cliente->nombre_cli;
                    $rut_cli = $cliente->rut_cli;
                    $rut_usu = $cliente->rut_usu;
                    $giro = $cliente->giro_cli;
                    $telefono = $cliente->telefono_cli;
                    $visitas = 0;
                    $plan = $cliente->tipo_plan;

                    //Se valida de que exista a lo menos una categoria ingresada
                    if ($_REQUEST["categorias"] != " ") {

                        //Se obtienen las categorias enviadas
                        $array_categorias_enviadas = $_REQUEST["categorias"];

                        //El string obtenido se pasa a array
                        $array_categorias = explode(".", $array_categorias_enviadas);

                        //Se elimina el espacio en blanco al principio del array
                        unset($array_categorias[0]);

                        $sentencia_categoria = new categoria();

                        if ($cliente->sql("insert into Cliente (id_usu,username_usu,password_usu,rol_usu,nombre_usu,apellido_usu,email_usu,estado_usu,lenguaje_usu,nombre_cli,rut_cli,giro_cli,telefono_cli,visitas_cli,tipo_plan,rut_usu) values(" . $id . ",'" . $username_usu . "','" . $password_usu . "','" . $rol_usu . "','" . $nombre_usu . "','" . $apellido_usu . "','" . $email_usu . "','" . $estado_usu . "','" . $lenguaje_usu . "','" . $nombre_cli . "','" . $rut_cli . "','" . $giro . "','" . $telefono . "'," . $visitas . ",'" . $plan . "','" . $rut_usu . "');")) {//&&($user->sql("update Usuario set rol_usu='cliente' where id=".$id)))
                            foreach ($array_categorias as &$categoria) {
                                //Traduccion de las categorias
                                $categoria_ingles = "";
                                switch ($categoria):
                                    case "Playa":
                                        $categoria_ingles = "Beach";
                                        break;
                                    case "Lago":
                                        $categoria_ingles = "Lake";
                                        break;
                                    case "Río":
                                        $categoria_ingles = "River";
                                        break;
                                    case "Piscina":
                                        $categoria_ingles = "Pool";
                                        break;
                                    case "Calido":
                                        $categoria_ingles = "Warm";
                                        break;
                                    case "Nieve":
                                        $categoria_ingles = "Snow";
                                        break;
                                    case "Lluvioso":
                                        $categoria_ingles = "Rainy";
                                        break;
                                    case "Templado":
                                        $categoria_ingles = "Temperate";
                                        break;
                                    case "Tercera edad":
                                        $categoria_ingles = "Seniors";
                                        break;
                                    case "Juvenil":
                                        $categoria_ingles = "Youth";
                                        break;
                                    case "Familiar":
                                        $categoria_ingles = "Juvenile";
                                        break;
                                    case "Terrestres":
                                        $categoria_ingles = "Terrestrial";
                                        break;
                                    case "Extremos":
                                        $categoria_ingles = "Extremes";
                                        break;
                                    case "Acuáticos":
                                        $categoria_ingles = "Aquatic";
                                        break;
                                    case "Aventura":
                                        $categoria_ingles = "Adventure";
                                        break;
                                    case "Histórico":
                                        $categoria_ingles = "Historical";
                                        break;
                                    case "Festivo":
                                        $categoria_ingles = "Festive";
                                        break;
                                    case "Gastronómico":
                                        $categoria_ingles = "Gastronomic";
                                        break;
                                    case "Televisión":
                                        $categoria_ingles = "TV";
                                        break;
                                    case "Calefacción":
                                        $categoria_ingles = "Heating";
                                        break;
                                    case "Baños individuale":
                                        $categoria_ingles = "Individual bathrooms";
                                        break;
                                    case "Duchas":
                                        $categoria_ingles = "Showers";
                                        break;
                                    case "Duchas individuales":
                                        $categoria_ingles = "Individual showers";
                                        break;
                                    default:
                                        $categoria_ingles = $categoria;
                                endswitch;
                                //Fin traduccion de las categorias
                                //Se inserta la categoria en la tabla categoria
                                $sentencia_categoria->sql("INSERT INTO categoria(id_usu, nombre_cat, estado_cat, nombre_cat_eng) VALUES (" . $id . ", '" . $categoria . "', 'false', '" . $categoria_ingles . "')");
                            }
                            Flash::success("Solicitud enviada correctamente");
                            Input::delete();
                            Router::redirect("solicitud/ingresar/" . $id);
                        } else {
                            Flash::error("Error en el ingreso del cliente");
                        }
                    } else {
                        Flash::error("Debe ingresar a lo menos una categoria");
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
                            //Flash::success("Solicitud modificada correctamente");
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
        //$client = $client->find($id);
        //
        //se pregunta si existe el cliente
        if ($client->buscar_cliente($id)) {
            if ($client->estado_usu == "t") {
                $this->id_cliente = $id;
                $this->telefono = $client->telefono_cli;
                $this->nombre_cliente = $client->nombre_cli;
                $this->mostrar = $client->visitas_cli + 1;
            }

            //Obtenemos la ubicacion del centro
            $ubicacion = new Ubicacion();
            $ubicacion = $ubicacion->find_by_sql("select *  from ubicacion where id_usu = " . $id . "and estado_ubi=TRUE");
            if ($ubicacion != null) {
                $this->region_ubi = $ubicacion->region_ubi;
                $this->ciudad_ubi = $ubicacion->ciudad_ubi;
                $this->direccion_ubi = $ubicacion->direccion_ubi;
            }
            
            
            $categoria_cli = new Categoria();
            $this->array_categorias = $categoria_cli->find("conditions: id_usu=" . $id . "and estado_cat='t'");
            
            

            //Comprueba que no se actualise el contador de visita si es el mismo dueño del centro turistico
            if (Session::get("id") == $id) {
                $captura = $client->visitas_cli;
                $actualiza = $captura + 0;
                $client->sql("update Cliente set visitas_cli=" . $actualiza . "where id_usu=" . $id . "and estado_usu=TRUE");
            }
            if (Session::get("id") != $id) {
                $captura = $client->visitas_cli;
                $actualiza = $captura + 1;
                $client->sql("update Cliente set visitas_cli=" . $actualiza . "where id_usu=" . $id . "and estado_usu=TRUE");
            }
            if (Session::get("id") == null) {
                $captura = $client->visitas_cli;
                $actualiza = $captura + 1;
                $client->sql("update Cliente set visitas_cli=" . $actualiza . "where id_usu=" . $id . "and estado_usu=TRUE");
            }



            $contenido = new Contenido();
            $arr2 = $contenido->find("conditions: id_usu=" . $id . "and estado_con='t'");
            $this->contenido = $arr2;
            $contimg = 0;
            foreach ($arr2 as $contenido) {

                $this->ruta_con[$contimg] = $arr2[$contimg]->ruta_con;


                $contimg++;
            }

            $this->contimg = $contimg;

            $services = new Servicio();
            $services = $services->find("conditions: id_usu=" . $id . "and estado_ser='t'");
            $this->array_servicios = $services;

            //2- Necesario para cargar el mapa
            $ubicacion = new Ubicacion();
            $ubicacion = $ubicacion->find("conditions: id_usu=" . $id . "and estado_ubi='t'");
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
            $result = $calificacion->count("conditions: cli_id_usu=" . $id . "and estado_cal=TRUE");


            if ($result != 0) {

                $promedio = $calificacion->average("valor_cal", "conditions: cli_id_usu=" . $id . "and estado_cal=TRUE");
                $this->calificacion = $promedio;
                $this->cantidad = $result;
            } else {

                $this->calificacion = 0;
                $this->cantidad = $result;
            }

            //mandar nombre de usuario para el ajax.
            if (Auth::is_valid()) {
                $id_usuario = Auth::get("id");
                $nombre = $user->find($id_usuario);
                $this->nombre_usuario = $nombre->username_usu;
            }
        } else {
            Flash::info("Direccion no encontrada");
            Router::redirect("/");
        }
    }

    /////////////////////////////////////////////////    
    //***Funciones relacionadas con la solicitud***//
    //*********************************************//
    ////////////////////////////////////////////////
    //Funcion para modificar los datos del cliente una vez ingresada al solicitud.
    public function ver($id) {
        $vercliente = new cliente();

        if (Input::hasPost('cliente')) {

            $cliente = new Cliente(Input::post('cliente'));

            $solicitud_modificacion = new Solicitud ();
            $solicitud_modificacion = $solicitud_modificacion->buscar_solicitud($id);
            $solicitud_modificacion->modificaciones_sol = "true";

            //Paso de datos desde usuario encontrado a cliente a ingresar
            $username_usu = $cliente->username_usu;
            $password_usu = $cliente->password_usu;
            $nombre_usu = $cliente->nombre_usu;
            $apellido_usu = $cliente->apellido_usu;
            //$rut_usu = $cliente->rut_usu;
            $email_usu = $cliente->email_usu;
            $nombre_cli = $cliente->nombre_cli;
            //$rut_cli = $cliente->rut_cli;
            $giro = $cliente->giro_cli;
            $telefono = $cliente->telefono_cli;
            $plan = $cliente->tipo_plan;


            if ($cliente->sql("UPDATE cliente SET nombre_cli='" . $nombre_cli . "', giro_cli='" . $giro . "', telefono_cli='" . $telefono . "', tipo_plan='" . $plan . "' WHERE id_usu =" . $id . ";") && $solicitud_modificacion->update()) {
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

    //Funcion para poder renovar la solicitud o poder ver los datos del cliente y de la solicitud

    public function administrarsuscripcion($leng) {
        if (Auth::is_valid()) {
            if (Auth::get("rol_usu") == "cliente") {
                $this->leng = $leng;
                $this->estado_sol = "1";
                $datos_cliente = new cliente();
                $id_cliente = Auth::get("id");

                if ($datos_cliente->find_by_sql("select * from cliente where id_usu=" . $id_cliente)) {
                    //*********************************
                    //Se obtienen los datos del cliente
                    //*********************************
                    $this->nombre_cli = $datos_cliente->nombre_cli;
                    $this->nombre_usu = $datos_cliente->nombre_usu;
                    $this->rut_usu = $datos_cliente->rut_usu;
                    $this->rut_cli = $datos_cliente->rut_cli;
                    $this->nombre_usu = $datos_cliente->nombre_usu;
                    $this->giro_cli = $datos_cliente->giro_cli;
                    $this->telefono_cli = $datos_cliente->telefono_cli;
                    $this->tipo_plan = $datos_cliente->tipo_plan;
                    $this->ini_sus = $datos_cliente->fecha_ini_sus;

                    $tipo_plan = $datos_cliente->tipo_plan;
                    //$this->fin_sus = $datos_cliente->fecha_fin_sus;
                    //En caso de que el plan sea free se muestra el mensaje indefinido
                    $fin = $datos_cliente->fecha_fin_sus;
                    if ($fin == "") {
                        $this->fin_sus = "Indefinido";
                    } else {
                        $this->fin_sus = $datos_cliente->fecha_fin_sus;
                    }
                    //Fin mensaje indefinido
                    //*********************************
                    //Fin obtencion datos del cliente
                    //*********************************
                    //
                    //******************************************************************
                    //Se obtienen los datos de la solicitud aceptada asociada al cliente
                    //******************************************************************
                    $datos_solicitud = new solicitud();
                    $datos_solicitud->solicitud_aceptada($id_cliente); // obtengo los datos de la solicitud

                    $this->fecha_solicitud = $datos_solicitud->fecha_sol;
                    $this->observaciones = $datos_solicitud->observaciones_sol;
                    $this->id_solicitud = $datos_solicitud->id;
                    //******************************************************************
                    //Fin obtencion datos de la solicitud aceptada asociada al cliente
                    //******************************************************************     
                    // 
                    ////////////////////////////////////////////////////////////////////////////////////////      
                    ///////////////////////////////////////////////////////////////////////////////////////           
                    /////////////////////////////////MODULO DE RENOVACIÓN///////////////////////////////// 
                    /////////////////////////////////////////////////////////////////////////////////////
                    ////////////////////////////////////////////////////////////////////////////////////
                    //    
                    //**************************************
                    //Para poder mostar el boton de renovar
                    //**************************************
                    //Se revisa que el tipo de plan sea normal o plus
                    if ($tipo_plan != "free") {
                        date_default_timezone_set('America/Santiago');
                        //Se obtiene la fecha actual
                        $fecha_actual = date("Y-m-d");
                        
                        $comprobar = new solicitud(); // sirve para validar que no exista otra solicitud de renovacion
                        //Se obtiene la fecha del fin suscripcion
                        $fecha_fin_suscripcion = $datos_cliente->fecha_fin_sus;
                        //Flash::info($fecha_actual."    ".$fecha_fin_suscripcion);
                        //Paso el dia, mes y año para poder comprarlos despues
                        //list($ano, $mes, $dia) = explode('-', $fecha_fin_suscripcion);

                        //Se valida de que se cumpla las condiciones de fecha y de que no exista otra solicitud de renovacion
                        if ($fecha_fin_suscripcion <= $fecha_actual && !$comprobar->solicitud_renovacion($id_cliente)) {
                            // se se cumple las condiciones el boton puede ser mostrado
                            $this->muestra_boton = "Si";
                            $this->estado_sol = "1";
                        } else {
                            $this->noexiste = "<center><b>Todavia  no cumple los requisitos</b></center>";
                            $this->estado_sol = "1";
                        }

                        //******************
                        //Fin boton renovar
                        //******************
                        //
                    //************************************************************
                        //Leyendas y panel de administracion de la nueva solicitud de 
                        //administracion, cuando el boton renovar no aparece despues de haber 
                        //mandado la solicitud de renovacion
                        //************************************************************

                        $leyendas = new solicitud();
                        if ($leyendas->solicitud_renovacion($id_cliente)) {
                            //Se obtienen los datos de la solicitud renovacion, para poder mostrar el panel de administracion de la misma

                            $this->panel_suscripcion_renovacion = $leyendas->tipo_sol;
                            $this->estado_sol = $leyendas->estado_sol;
                            $this->fecha_sol = $leyendas->fecha_sol;
                            $this->observaciones_sol = $leyendas->observaciones_sol;
                            $this->id_usu = $leyendas->id_usu;
                            $this->mail_sol = $leyendas->mail_sol;
                        } else {
                            //$this->existe ="<center><b>Ya tiene una solicitud de renovacion pendiente</b></center>";
                            $this->estado_sol = "1";
                        }
                        //************************************************
                        //Fin leyendas cuando el boton renovar no aparece
                        //************************************************
                        ////////////////////////////////////////////////////////////////////////////////////////      
                        ///////////////////////////////////////////////////////////////////////////////////////           
                        ///////////////////////////////FIN MODULO DE RENOVACIÓN/////////////////////////////// 
                        /////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////  
                    }else if ($tipo_plan == "free") {
                        //////////////////////////////
                        //////MODULO CAMBIO PLAN/////
                        ////////////////////////////
                        $solicitud_cambio_existe = new solicitud;
                        
                        if ($solicitud_cambio_existe->verifica_solicitud_cambio(Auth::get("id")))
                        {
                            $this->activacion_panel_cambio_plan= "No mostrar";
                            $this->estado = $solicitud_cambio_existe->estado_sol;
                            $this->mail = $solicitud_cambio_existe->mail_sol;
                        }
                        else
                        {
                            $this->activacion_panel_cambio_plan= "Mostrar";
                        }
                        //////////////////////////////////
                        //////FIN MODULO CAMBIO PLAN/////
                        ////////////////////////////////
                    }
                } else {
                    Flash::info('No tiene suscripcion activa');
                    Router::redirect("cliente/ingresarsolicitud");
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::info('Debe iniciar sesión');
            Router::redirect("/");
        }
    }

    public function eliminar($id, $leng) {
        $this->leng = $leng;
        if (Auth::is_valid()) {
            if (Auth::get("rol_usu") == "administrador") {

                $cliente_eliminar = new cliente ();

                if ($cliente_eliminar->buscar_cliente($id)) {
                    //Creacion objetos para eliminar los datos
                    $ubicacion = new ubicacion;
                    $categorias = new categoria;
                    $servicios = new servicio;
                    $contenidos = new contenido;
                    $calificaciones = new calificacion;
                    $cliente = new cliente;
                    $usuario = new usuario;

                    //Se obtiene el correo para mandarlo al cliente a eliminar  
                    $correo = $cliente_eliminar->email_usu;

                    //Se crean las sentencias para actualizar
                    $sentencia_ubicacion = "UPDATE ubicacion SET estado_ubi=false WHERE id_usu=" . $id;
                    $sentencia_categoria = "UPDATE categoria SET estado_cat=false WHERE id_usu=" . $id;
                    $sentencia_servicio = "UPDATE servicio SET estado_ser=false WHERE id_usu=" . $id;
                    $sentencia_contenido = "UPDATE contenido SET estado_con=false WHERE id_usu=" . $id;
                    $sentencia_calificacion = "UPDATE calificacion SET estado_cal=false WHERE cli_id_usu=" . $id;
                    $sentencia_cliente = "UPDATE cliente SET estado_usu=false, rol_usu='turista' WHERE id_usu=" . $id;
                    $sentencia_usuario = "UPDATE usuario SET rol_usu='turista' WHERE id=" . $id;

                    if ($ubicacion->sql($sentencia_ubicacion) &&
                            $categorias->sql($sentencia_categoria) &&
                            $servicios->sql($sentencia_servicio) &&
                            $contenidos->sql($sentencia_contenido) &&
                            $calificaciones->sql($sentencia_calificacion) &&
                            $cliente->sql($sentencia_cliente) &&
                            $usuario->sql($sentencia_usuario)) {

                        // Se crea el correo electronico
                        require(APP_PATH . 'libs/PHPMailer_5.2.2-rc2/class.phpmailer.php');
                        require(APP_PATH . 'libs/PHPMailer_5.2.2-rc2/class.smtp.php');
                        $mail = new PHPMailer();
                        $mail->IsSMTP();
                        //$mail->SMTPSecure = "ssl";
                        $mail->SMTPAuth = true;
                        $mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
                        $mail->Host = "smtp.mail.yahoo.com";
                        $mail->Port = 25;
                        $mail->Username = "qualitytoursadm@yahoo.com";
                        $mail->Password = "qt123123";

                        //Preparar el mail
                        //$mail->From = $_POST['email'];
                        $mail->From = "qualitytoursadm@yahoo.com";
                        $mail->FromName = "Administrador";
                        $mail->Subject = "Cancelaci&oacute;n suscripci&oacute;n Qualiy tours"; //ASUNTO DEL CORREO
                        $mail->Body = stripcslashes("Su suscripci&oacute;n ha sido cancelada, para saber los motivos puede contactarse al mismo email del remitente"); //EL CONTENIDO DEL CORREO
                        $mail->AddAddress($correo, "Destinatario"); //Dirección a la que enviaremos el correo
                        $mail->IsHTML(true);
                        $mail->send();

                        Flash::info('Suscripcion cliente cancelada');
                        Router::redirect("usuario/buscar/" . $leng);
                    } else {
                        Flash::error('No se pudo cancelar la suscripcion');
                        Router::redirect("usuario/buscar/" . $leng);
                    }
                } else {
                    Flash::info('Datos incorrectos');
                    Router::redirect("usuario/buscar/" . $leng);
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::info('Debe iniciar sesión');
            Router::redirect("/");
        }
    }

}

