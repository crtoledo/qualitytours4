<?php
Load::model('publicacion');
Load::model('contenido');
Load::model('cliente');
Load::model('calificacion');
Load::model('categoria');
/**
 * Controller por defecto si no se usa el routes
 * 
 */
class IndexController extends AppController 
{
	public function index()
	{
            //se captura por metodo get debido a que el index no se pueden pasar parametros de la forma normal 
            if($_GET["l"] == null)
            {
                $this->leng = "es";
            }
            else
            {
                $this->leng = "en";
            }
            //Creamos objetos que necesitaremos
            $publicacion = new Publicacion();
            //validar si existen publicaciones
            if($publicacion->count("conditions: estado_pub='t' and detalle_pub_eng != ''") == 0)
            {
              $this->cont = 0;
            }
            else
            {
              $this->cont = 1;
            }
            //Capturan los valores de la publicación
             $arr= $publicacion->find("conditions: estado_pub='t' and detalle_pub_eng != ''","order: id DESC");
             //recorre el arreglo de publicacion
             $contador = 0;
             foreach ($arr as $publicacion )
             {
               if($_GET["l"] == null)
               {
                    $this->titulo[$contador] = $arr[$contador]->titulo_pub; 
                    $this->detalle[$contador] = $arr[$contador]->detalle_pub;
                    $this->id_pub[$contador] = $arr[$contador]->id;
               }
               else
               {    
                    //variables en inglés
                    $this->titulo[$contador] = $arr[$contador]->titulo_pub_eng;
                    $this->detalle[$contador] = $arr[$contador]->detalle_pub_eng;
                    $this->id_pub[$contador] = $arr[$contador]->id;
               }     
                //crear objeto
                $contenido = new Contenido();
                //BUCAR IMAGENES ASOCIADAS AL ID DE LA PUBLICACION 
                //validar si existen contenido
                if ($contenido->count("conditions: id_pub=".$publicacion->id." and  estado_con='t'") == 0 )
                {
                    $this->contenido[$contador]= 0;
                }
                else
                {
                    $this->contenido[$contador] = 1;
                }
                $imagen= $contenido->find("conditions: id_pub=".$publicacion->id." and  estado_con='t'");
              
                foreach($imagen as $contenido)
                {
                    //$this->ruta[$contador] = $imagen[$contador]->ruta_con;
                    $this->ruta[$contador] = $contenido->ruta_con;
                }
                $this->fecha[$contador] = $arr[$contador]->fecha_pub;
                $this->id[$contador] = $arr[$contador]->id;
                $contador++;
             }
                $this->contador = $contador;
                
                //ranking de visitas
                $cliente =  new Cliente();
                $client = $cliente->find("conditions: estado_usu='t'","limit: 3","order: visitas_cli desc");
                $i = 0;
                foreach($client as $cliente)
                {
                    $this->nombre_cli[$i] = $cliente->nombre_cli;
                    $this->id_cliente[$i] = $cliente->id_usu;
                    $i++;
                }
                $this->indice = $i;
                
                //ranking de calificacion
                $calificacion =  new Calificacion();
                $cal = $calificacion->find('columns: cli_id_usu,avg(valor_cal)','group: cli_id_usu','order: avg desc','limit: 3',"conditions: estado_cal='t'");
                
                $contador = 0;
                $cliente = new Cliente();
                
                foreach($cal as $promedio)
                {
                 
                    $id[$contador] = $promedio->cli_id_usu;
                    $this->id[$contador]=$id[$contador];
                    $nombre[$contador] = $cliente->find($id[$contador]);
                    $this->id_cli_cal[$contador] = $nombre[$contador]->nombre_cli;
                    $this->valor_cal[$contador] = $promedio->avg;
                    $contador++;
                  
                }
                $this->contadors = $contador;
               //FIN RANKING CALIFICACION
                
                //COMIENZO ITEMS TAG CLOUD
                $categoria_obj = new Categoria();
                $leng = $this->leng;
                $contador_cat = 0;
                //$categorias_encontradas = $categoria_obj->find_all_by('estado_cat',1);
                //$categorias_encontradas = $categoria_obj->find('conditions: estado_cat=TRUE','limit: 7');
                
                
                  $categorias_encontradas = $categoria_obj->find_all_by_sql("
                    select * from 
                    (select distinct nombre_cat, nombre_cat_eng
                    from categoria where estado_cat=TRUE) s

                    order by random()
                    limit 10");
                  
                 
                
                    foreach ($categorias_encontradas as $cat)
                    {
                        //$this->cat[$contador_cat] = $cat[$contador_cat]->nombre_cat;
                        if ($leng == "en") $this->cat[$contador_cat] = $cat->nombre_cat_eng;
                        else $this->cat[$contador_cat] = $cat->nombre_cat;
                        $contador_cat++;
                    }
                
                
                $this->contador_cat = $contador_cat;



        //FIN ITEMS TAG CLOUD
	}
        public function en()
        {
            Router::redirect("index/?l=en");
        }
        
        public function ranking($leng)
        {
            $this->leng = $leng;
            
             //ranking de visitas
                $cliente =  new Cliente();
                $client = $cliente->find("conditions: estado_usu='t'","limit: 30","order: visitas_cli desc");
                $i = 0;
                foreach($client as $cliente)
                {
                    $this->nombre_cli[$i] = $cliente->nombre_cli;
                    $this->id_cliente[$i] = $cliente->id_usu;
                    $this->visitas[$i] = $cliente->visitas_cli;
                    $i++;
                }
                $this->indice = $i;
                
                //ranking de calificacion
                $calificacion =  new Calificacion();
                $cal = $calificacion->find('columns: cli_id_usu,avg(valor_cal)','group: cli_id_usu','order: avg desc','limit: 30',"conditions: estado_cal='t'");
                
                $contador = 0;
                $cliente = new Cliente();
                
                foreach($cal as $promedio)
                {
                 
                    $id[$contador] = $promedio->cli_id_usu;
                    $this->id[$contador]=$id[$contador];
                    $nombre[$contador] = $cliente->find($id[$contador]);
                    $this->id_cli_cal[$contador] = $nombre[$contador]->nombre_cli;
                    $this->valor_cal[$contador] = $promedio->avg;
                    $contador++;
                  
                }
                $this->contadors = $contador;
            
        }
        public function servicio($leng)
        {
            $this->leng = $leng;
        }
       
        
       
}
?>
