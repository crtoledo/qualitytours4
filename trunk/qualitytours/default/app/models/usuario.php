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
    
       public function before_create()
   {
       //valida que solo exista un rut
       if($this->exists("rut_usu='".$this->rut_usu."'"))
       { 
           Flash::error('El rut ingresado ya se encuentra en nuestros registros.'); 
           return 'cancel'; 
       }
       //valida que solo exista un username
       if($this->exists("username_usu='".$this->username_usu."'"))
       { 
           Flash::error('El username ingresado ya se encuentra en nuestros registros.'); 
           return 'cancel'; 
       }
       //valida que solo exista un email
       if($this->exists("email_usu='".$this->email_usu."'"))
       { 
           Flash::error('El email ingresado ya se encuentra en nuestros registros.'); 
           return 'cancel'; 
       }
   }
    
    public function getdatosusuarios($page,$datoabuscar, $ppage=10)
    {
       return $this->paginate("username_usu LIKE '%".$datoabuscar."%' and estado_usu=TRUE and rol_usu='turista'" ,"page: $page", "per_page: $ppage");
    }
    
    public function getdatosclientes($page,$datoabuscar, $ppage=10)
    {
       return $this->paginate("username_usu LIKE '%".$datoabuscar."%' and estado_usu=TRUE and rol_usu='cliente'" ,"page: $page", "per_page: $ppage");
    }
    
    public function getdatosambos($page,$datoabuscar, $ppage=10)
    {
       return $this->paginate("username_usu LIKE '%".$datoabuscar."%' and estado_usu=TRUE and rol_usu<>'administrador'" ,"page: $page", "per_page: $ppage");
    }

}
?>

