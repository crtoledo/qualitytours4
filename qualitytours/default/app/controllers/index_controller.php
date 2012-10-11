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
           
            //Capturan los valores de la publicación
             $arr= $publicacion->find("conditions: estado_pub='t'","order: id DESC");
       
       
              //validar si existen publicaciones
           if ($publicacion->count() == 0)
            {
               $this->cont = 0;
              
            }
            else
            {
                $this->cont = 1;
            }
             //recorre el arreglo de publicacion
             foreach ($arr as $publicacion ) 
             {
                $this->titulo = $publicacion->titulo_pub; 
                $this->detalle = $publicacion->detalle_pub;
                   //crear objeto
                   $contenido = new Contenido();
                   //BUCAR IMAGENES ASOCIADAS AL ID DE LA PUBLICACION 
                  //validar si existen contenido
                   if ($contenido->count() == 0)
                    {
                        $this->contenido = 0;
              
                    }
                    else
                    {
                        $this->contenido = 1;
                     }
                      $imagen= $contenido->find("conditions: id_pub=".$publicacion->id);
                       foreach($imagen as $buscar)
                       {
                           $this->ruta = $buscar->ruta_con;
                           
                           
                       }
                 
                $this->fecha = $publicacion->fecha_pub;
                $this->id = $publicacion->id;
             }
		
	}
}
