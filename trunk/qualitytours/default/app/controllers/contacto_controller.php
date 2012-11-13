<?php
load::model('usuario');

class ContactoController extends AppController {

    public function before_filter() {

    //verifica si se encuentra logueado
        if (!Auth::is_valid()) {
            Flash::info('Debe iniciar sesión');
            Router::redirect("/");
        }
    }

    public function contacto_adm()
    {
        $usuario = new Usuario();
        $usuario->find(Auth::get('id'));
        $this->username_usu = $usuario->username_usu;
        $this->email_usu = $usuario->email_usu;
    }

}

?>
