<?php //
class Usuario extends ActiveRecord
{
    public function initialize()
    {
     //validaciones de campos (que no esten vacíos)
//     $this->validates_presence_of('nombre_usu', 'message: Debe ingresar un nombre');
//     $this->validates_presence_of('password_usu', 'message: Debe ingresar una password');
//     $this->validates_presence_of('username_usu', 'message: Debe ingresar un username');
//     $this->validates_presence_of('rol_usu','message: Debe seleccionar un rol');
//     $this->validates_presence_of('apellido_usu', 'message: Debe ingresar un apellido');
//     $this->validates_presence_of('rut_usu', 'message: Debe ingresar un rut');
//     
     //Validacion de formato e-mail
//     $this->validates_email_in('email_usu','message: Debe ingresar un mail válido');
     
     //Validaciones de longitud
//     $this->validates_length_of("username_usu", $max=20,$min=5);
//     $this->validates_length_of("password_usu", $max=20,$min=5);
        
     //Validar que estos campos ya no existan en la base de datos (campos unicos)
//     $this->validates_uniqueness_of("rut_usu",'message: El rut ingresado ya se encuentra en nuestros registros');
//     $this->validates_uniqueness_of("username_usu",'message: El username ingresado ya se encuentra en nuestros registros');
//     $this->validates_uniqueness_of("email_usu",'message: El correo ingresado ya se encuentra en nuestros registros');
//     
     //Valida que el rut este con puntos (valida formato 00.000.000-k) (no valida que el rut sea válido!!)
//     $this->validates_format_of('rut_usu',"^\d{1,2}\.\d{3}\.\d{3}[-][0-9kK]{1}$^",'message: El rut ingresado debe llevar puntos y guión');
     //la expresion regular original es ^\d{1,2}\.\d{3}\.\d{3}[-][0-9kK]{1}$
     //pero por alguna razón kumbia necesita que se le agregue ^ al final
    }

}
?>

