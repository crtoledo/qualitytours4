<?php
load::model('usuario');
load::model('cliente');

class ContactoController extends AppController {

    public function before_filter() {

    
    }

    public function contacto($leng)
    {
       $this->leng = $leng;
    }
    
    public function contacto_cli($id_cli,$leng)
    {
        //verifica si se encuentra logueado
        if (!Auth::is_valid()) {
            Flash::info('Debe iniciar sesión');
            Router::redirect("/");
        }
        
        $this->leng = $leng;
        $usuario = new Usuario();
        $usuario->find(Auth::get('id'));
        $this->username_usu = $usuario->username_usu;
        $this->email_usu = $usuario->email_usu;
        
        $cliente = new Cliente();
        $cliente->find($id_cli);
        $this->email_cli = $cliente->email_usu;
        $this->nombre_cli = $cliente->nombre_cli;
    }

}

?>
