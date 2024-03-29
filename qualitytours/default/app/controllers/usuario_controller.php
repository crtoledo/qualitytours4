<?php

load::model('usuario');
load::model('cliente');

class UsuarioController extends AppController {

    public function before_filter() {
        
    }

    public function index() {
        
    }

    public function ingresar($leng) {
        $this->leng = $leng;
        if (!Auth::is_valid()) {
            if (Input::hasPost('usuario')) {
                $usuario = new Usuario(Input::post('usuario'));
                
                //ELIMINACION DE APOSTROFES
                $usuario->apellido_usu = str_replace("'", '', $usuario->apellido_usu);
                $usuario->nombre_usu = str_replace("'", '', $usuario->nombre_usu);
                
                $contraseña = $usuario->password_usu;

                $en1 = md5($contraseña);
                unset($contraseña);
                $en2 = sha1($en1);
                unset($en1);
                $en3 = hash('sha512', $en2);
                unset($en2);
                $usuario->password_usu = $en3;
                unset($en3);

                if (!$usuario->save()) {
                    if ($leng == "es") {
                        Flash::error('Error al agregar Usuario');
                    } else {
                        Flash::error('user add error');
                    }
                } else {
                    if ($leng == "es") {
                        Flash::success('Usuario ingresado satisfactoriamente');
                        Router::redirect("index");
                    } else {
                        Flash::success('Add user successful');
                        Router::redirect("index/?l=en");
                    }
                }
            }
        } else {
            Flash::error('Usted ya se encuentra registrado');
            Router::redirect("/");
        }
    }

    public function modificar($id, $leng) {
        $this->leng = $leng;
        if (Auth::is_valid()) {
            if (Auth::get('rol_usu') == 'administrador') {
                $usuarioamodificar = new Usuario;
                $verificarrol = $usuarioamodificar->find($id);

                if ($verificarrol->rol_usu == 'turista') {
                    if (Input::hasPost('usuario')) {
                        $usuarioactualizado = new usuario;

                        if ($usuarioactualizado->update(Input::post('usuario'))) {
                            Flash::info("Usuario " . $verificarrol->username_usu . " modificado con exito");
                            Router::redirect("usuario/buscar/" . $leng);
                        }
                    } else {
                        $this->diferenciador = '1';
                        $this->usuario = $verificarrol;
                    }
                } else {
                    $clienteamodificar = new cliente;

                    if (Input::hasPost('cliente')) {
                        //datos para modificar el usuario en la tabla cliente
                        $clienteactualizado = new Cliente(Input::post('cliente'));

                        //datos para actualizar al usaurio en la tabla usuario
                        $usuariotabla = new Usuario;
                        $modificaciontablausuario = $usuariotabla->find($id);

                        //se transpasan los nuevos datos del cliete para su actualizacion en la tabla usuario.
                        $usuariotabla->id = $clienteactualizado->id_usu;
                        $usuariotabla->username_usu = $clienteactualizado->username_usu;
                        $usuariotabla->password_usu = $clienteactualizado->password_usu;
                        $usuariotabla->nombre_usu = $clienteactualizado->nombre_usu;
                        $usuariotabla->apellido_usu = $clienteactualizado->apellido_usu;
                        $usuariotabla->rut_usu = $clienteactualizado->rut_usu;
                        $usuariotabla->email_usu = $clienteactualizado->email_usu;
                        $usuariotabla->estado_usu = $clienteactualizado->estado_usu;

                        if ($clienteactualizado->update() && $usuariotabla->update()) {
                            Flash::info("Cliente " . $verificarrol->username_usu . " modificado con exito");
                            Router::redirect("usuario/buscar/" . $leng);
                        } else {
                            Flash::error("Error en la modificación");
                        }
                    } else {
                        $this->diferenciador = '2';
                        $this->cliente = $clienteamodificar->find($id);
                    }
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::info('Lo sentimos ubicaci&oacute;n no encontrada');
            Router::redirect("/");
        }
    }

    //funcion para que el usuario turista pueda modificar sus datos
    public function automodificar($leng) {
        if (Auth::is_valid()) {
            $this->leng = $leng;
            if (Auth::get('rol_usu') == 'turista') {
                $usuarioaeditar = new usuario();
                if (Input::hasPost('usuario')) {
                    $usuarioupdate = new Usuario(Input::post('usuario'));

                    $contraseña = $usuarioupdate->password_usu;

                    $en1 = md5($contraseña);
                    unset($contraseña);
                    $en2 = sha1($en1);
                    unset($en1);
                    $en3 = hash('sha512', $en2);
                    unset($en2);
                    $usuarioupdate->password_usu = $en3;
                    unset($en3);

                    if ($usuarioupdate->update()) {
                        if($leng == "es")
                        {
                            Flash::valid('Tus datos fueron actualizados');
                            Router::redirect('/');
                        }
                        else
                        {
                             Flash::valid('Data was updated');
                             Router::redirect('index/?l=en');
                        }
                        
                    } else {
                        Flash::error('Error al actualizar tus datos');
                    }
                } else {
                    $usuario = $usuarioaeditar->find((int) Auth::get('id'));
                    $usuario->password_usu = "";
                    $this->usuario = $usuario;
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::info('Lo sentimos ubicaci&oacute;n no encontrada');
            Router::redirect("/");
        }
    }

    public function buscar($leng) {
        if (Auth::is_valid()) {
            if (Auth::get("rol_usu") == "administrador") {
                $this->leng = $leng;
            } else {
                if ($leng == "en") {
                    echo Flash::info("location not found");
                    Router::redirect("index/?l=en");
                } else {
                    echo Flash::info("Ubicaci&oacute;n no encontrada");
                    Router::redirect("/");
                }
            }
        } else {
            if ($leng == "en") {
                echo Flash::info("location not found");
                Router::redirect("index/?l=en");
            } else {
                echo Flash::info("Lo sentimos ubicaci&oacute;n no encontrada");
                Router::redirect("/");
            }
        }
    }

    public function admin() {
        
    }

    public function eliminar($id, $leng) {
        if (Auth::is_valid()) {
            if (Auth::get('rol_usu') == 'administrador') {
                $this->leng = $leng;
                // Se valida de que el id que recibe sea numerico
                if (is_numeric($id)) {
                    $usuario_eliminar = new usuario;
                    $usuario_eliminar->find($id);

                    //Obtengo el rol de usuario a eliminar
                    $rol_usuario = $usuario_eliminar->rol_usu;
                    //Se pregunta si es turista, de ser verdad se procede a eliminarlo
                    if ($rol_usuario == "turista") {
                        $usuario_eliminar->estado_usu = "false";
                        $nombre = $usuario_eliminar->username_usu;
                        if ($usuario_eliminar->update()) {
                            Flash::info('Usuario: ' . $nombre . ' eliminado correctamente');
                            Router::redirect("/usuario/buscar/" . $leng);
                        } else {
                            Flash::info('El usuario: ' . $nombre . ' no se pudo eliminar');
                            Router::redirect("/usuario/buscar/" . $leng);
                        }
                        //El usuario esta como cliente, por lo tanto primero debe ser cancelada su suscripcion para poder eliminarlo
                    } else if ($rol_usuario == "cliente") {
                        Flash::error('Para poder eliminar el usuario, debe cancelar la suscripcion primero');
                        Router::redirect("/usuario/buscar/" . $leng);
                    } else if ($rol_usuario == "administrador" || $rol_usuario == "traductor") {
                        Flash::error('El usuario tiene privilegios en la plataforma. no se puede eliminar');
                        Router::redirect("/usuario/buscar/" . $leng);
                    }
                } else {
                    Flash::info('Datos incorrectos');
                    Router::redirect("/usuario/buscar/" . $leng);
                }
            } else {
                Flash::info('No posee los privilegios necesarios');
                Router::redirect("/");
            }
        } else {
            Flash::info('Lo sentimos ubicaci&oacute;n no encontrada');
            Router::redirect("/");
        }
    }

    //Función para autenticar al usuario
    public function autenticar($len) {
        if (Input::hasPost("username_usu", "password_usu")) {
            $user = Input::post('username_usu');
            $pass = Input::post('password_usu');

            $en1 = md5($pass);
            unset($pass);
            $en2 = sha1($en1);
            unset($en1);
            $en3 = hash('sha512', $en2);
            unset($en2);

            $auth = new Auth("model", "class: usuario", "username_usu: $user", "password_usu: $en3", "estado_usu: true");
            unset($en3);

            if ($auth->authenticate()) {
                //captura el rol del usuario para futuros usos
                Session::set("rol_usu", Auth::get('rol_usu'));
                Session::set("id", Auth::get('id'));
                Session::set("username_usu", Auth::get('username_usu'));
                Session::set("lenguaje_usu","lenguaje_usu");

                if (Session::get('rol_usu') == 'administrador') {
                    $usuario = new Usuario();
                    $buscar = $usuario->find(Session::get("id"));
                    $idiom = $buscar->lenguaje_usu;

                    if ($idiom == "es") {
                        Flash::Success("Usuario logueado");
                        Router::redirect("/");
                    } else {
                        Flash::Success("Users logged in");
                        Router::redirect("index/?l=en");
                    }
                } else {
                    $usuario = new Usuario();
                    $buscar = $usuario->find(Session::get("id"));
                    $idiom = $buscar->lenguaje_usu;
                    if ($idiom == "es") {
                        Flash::Success("Usuario logueado");
                        Router::redirect("/");
                    } else {
                        Flash::Success("Users logged in");
                        Router::redirect("index/?l=en");
                    }
                }
            } else {
                if ($len == "es") {
                    $this->captura = "es";
                    Flash::error("Fallo en la autenticación: Nombre de usuario o contraseña incorrecto");
                    Router::redirect("/");
                }
                if ($len == "en") {
                    $this->captura = "en";
                    Flash::error("Authentication failed: Username or password incorrect");
                    Router::redirect("index/?l=en");
                }
            }
        } else {
            Flash::info("Ingrese sus datos de login");
            Router::redirect("/");
        }
    }

    public function cerrarsesion($leng) {
        if ($leng == "es") {
            Auth::destroy_identity();
            Flash::info("Sesión cerrada");
            Router::redirect("index");
        } else {
            Auth::destroy_identity();
            Flash::info("Closed Session");
            Router::redirect("index/?l=en");
        }
    }

    public function borrar() {
        if (Input::hasPost('usuario')) {
            
        }
    }

    public function leng($leng) //BUSCA EL LENGUAJE EN LA BD
    {
        $usuario = new Usuario();
        $buscar = $usuario->find(Session::get("id"));
        $idiom = $buscar->lenguaje_usu;
        
        

        if ($idiom == "en") {
            $this->lenguaje_usu = "en";
        } else {
            $this->lenguaje_usu = "es";
        }

        $this->lengselect = $leng;
    }

    public function leng2($leng) //UPDATE DEL LENGUAJE EN CASO DE QUE EL USUARIO LO CAMBIE 
            { 
        $id = Session::get("id");
        
        
        
        if ($leng == "es") {
            $usuario = new Usuario();
            $usuario->sql("UPDATE usuario set lenguaje_usu='es' WHERE id=" . $id);
            flash::info("A cambiado su idioma predeterminado a español");
            router::redirect("/");
        } else {
            $usuario = new Usuario();
            $usuario->sql("UPDATE usuario set lenguaje_usu='en' WHERE id=" . $id);
            flash::info("Changed the default language to English");
            router::redirect("index/?l=en");
        }
    }

    public function ingresart($leng) {
        $this->leng = $leng;
        if (Auth::is_valid()) {
            if (Input::hasPost('usuario')) {
                $usuario = new Usuario(Input::post('usuario'));

                $contraseña = $usuario->password_usu;

                $en1 = md5($contraseña);
                unset($contraseña);
                $en2 = sha1($en1);
                unset($en1);
                $en3 = hash('sha512', $en2);
                unset($en2);
                $usuario->password_usu = $en3;
                unset($en3);
                if (!$usuario->save()) {
                    if ($leng == "es") {
                        Flash::error('Error al agregar Usuario');
                    } else {
                        Flash::error('user add error');
                    }
                } else {
                    if ($leng == "es") {
                        Flash::success('Usuario ingresado satisfactoriamente');
                        Router::redirect("index");
                    } else {
                        Flash::success('Add user successful');
                        Router::redirect("index/?l=en");
                    }
                }
            }
        } else {

            Router::redirect("/");
        }
    }

    public function olvidar($leng) {
        $this->leng = $leng;
        if (Input::hasPost('buscar')) {
            $user = Input::post('buscar');
            //buscar si existe el nombre de usuario en la bd
            $usuario = new Usuario();
            if ($usuario->find_by_sql("select * from usuario where username_usu='" . $user . "'")) {
                //capuramos el correo y el nombre.. para enviarlo en el mail correspondiente.

                $nombre = $usuario->nombre_usu;
                $correo = $usuario->email_usu;
                //$clave = $usuario->password_usu;

                $nuevaclave = rand(50000, 100000);

                $en1 = md5($nuevaclave);
                $en2 = sha1($en1);
                unset($en1);
                $en3 = hash('sha512', $en2);
                unset($en2);

                $usuario->password_usu = $en3;
                unset($en3);

                if ($usuario->update()) {
                    //mandamos el correo correspondiente
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
                    $mail->Subject = "Restablecer contraseña";
                    $mail->Body = stripcslashes("Su nueva clave es: " . $nuevaclave . " y su nombre de usuario es: " . $user);
                    $mail->AddAddress($correo, "Destinatario"); //Dirección a la que enviaremos el correo
                    $mail->IsHTML(true);

                    if (!$mail->Send()) {
                        echo "Error: " . $mail->ErrorInfo;
                    } else {
                        //echo "Mensaje enviado correctamente";

                        if ($leng == "es") {
                            Flash::success("Su contraseña se ha mandado a su correo de registro ");
                            Router::redirect('/');
                        } else {
                            Flash::success("Your password is sent to your registration e-mail");
                            Router::redirect('/');
                        }
                    }
                } else {
                    if ($leng == "es") {
                        Flash::error("Su contraseña no se ha cambiado");
                        Router::redirect('/');
                    } else {
                        Flash::error("Password not changed");
                        Router::redirect('/');
                    }
                }
            } else {
                if ($leng == "es") {
                    echo flash::info("No existe el nombre de usuario");
                    Router::redirect("usuario/olvidar/" . $leng);
                } else {
                    echo flash::info("username does not exist");
                    Router::redirect("usuario/olvidar/" . $leng);
                }
            }
        }
    }

}
