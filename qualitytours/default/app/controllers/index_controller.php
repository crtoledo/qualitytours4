<?php
Load::model('publicacion');
Load::model('contenido');
/**
 * Controller por defecto si no se usa el routes
 * 
 */
class IndexController extends AppController 
{
	public function index()
	{
            //Creamos objetos que necesitaremos
            $publicacion = new Publicacion();
            //validar si existen publicaciones
            if($publicacion->count("conditions: estado_pub='t'") == 0)
            {
              $this->cont = 0;
            }
            else
            {
              $this->cont = 1;
            }
            //Capturan los valores de la publicación
             $arr= $publicacion->find("conditions: estado_pub='t'","order: id DESC");
             //recorre el arreglo de publicacion
             foreach ($arr as $publicacion ) 
             {
                $this->titulo = $publicacion->titulo_pub; 
                $this->detalle = $publicacion->detalle_pub;
                //crear objeto
                $contenido = new Contenido();
                //BUCAR IMAGENES ASOCIADAS AL ID DE LA PUBLICACION 
                //validar si existen contenido
                if ($contenido->count("conditions: estado_con='t'") == 0 )
                {
                    $this->contenido = 0;
                }
                else
                {
                    $this->contenido = 1;
                }
                $imagen= $contenido->find("conditions: id_pub=".$publicacion->id."and  estado_con='t'");
                foreach($imagen as $buscar)
                {
                    $this->ruta = $buscar->ruta_con;
                }
                $this->fecha = $publicacion->fecha_pub;
                $this->id = $publicacion->id;
             }
		
	}
}
?>