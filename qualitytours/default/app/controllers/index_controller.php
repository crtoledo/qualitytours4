<?php
/**
 * Controller por defecto si no se usa el routes
 * 
 */
class IndexController extends AppController 
{
	public function index()
	{
            //Capturan los valores de la publicación
             $arr= Load::model('publicacion')->find("conditions: estado_pub='t'","order: id DESC"); 
             
             //recorre el arreglo de publicacion
             foreach ($arr as $publicacion ) 
             {
                $this->titulo = $publicacion->titulo_pub; 
                $this->detalle = $publicacion->detalle_pub;
                   //BUCAR IMAGENES ASOCIADAS AL ID DE LA PUBLICACION 
                      $imagen= Load::model('contenido')->find("conditions: id_pub=".$publicacion->id);
                       foreach($imagen as $buscar)
                       {
                           $this->ruta = $buscar->ruta_con;
                           
                           
                       }
                 
                $this->fecha = $publicacion->fecha_pub;
                $this->id = $publicacion->id;
             }
		
	}
}
