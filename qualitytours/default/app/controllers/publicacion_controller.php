<?php
load::model('publicacion');
load::model('contenido');

class PublicacionController extends AppController 
{
     
    
    public function index()
    {
        
    }
    public function before_filter(){
        
//verifica si se encuentra logueado
		if(!Auth::is_valid())
                {
                        Flash::info('Debe iniciar sesión');
			Router::redirect("/");
                        
		}
                
            //verifica si el rol pertenece como corresponde
        
                  
	}
        
    public function editar($id,$leng)
    {
        //se captura el id para futuros usos
        $this->id_pub=$id;
        $this->leng= $leng;
    }

    public function ingresar($leng)
        { 
           $this->leng=$leng;
           //se valida que solo el administrador pueda ingresar una nueva publicación
           if(Auth::is_valid())
           {
               if(Auth::get("rol_usu") ==  "administrador")
               {
                    
                    if(Input::hasPost('publicacion'))
                    {

                            $publicacion = new Publicacion(Input::post('publicacion'));

                            $publicacion->detalle_pub = str_replace("'", '', $publicacion->detalle_pub);

                            if($publicacion->save())
                        {
                        Flash::success('Publicación ingresado satisfactoriamente');
                        Input::delete();
                        Router::redirect('/');
                        }
                        else
                        {

                            Flash::error('Error al agregar Publicación');

                        }

                    }
               }
               else
               {
                   router::Redirect("/");
               }
           }
           else
           {
              router::redirect("/");
           }
         
           
         } 
    
  
    
    public function eliminar($id)
    {
      if(Auth::is_valid())
      {
          if(Auth::get("rol_usu") == "administrador")
          {
            $publicacion = new Publicacion();
            $contenido = new Contenido();
            $contenido->sql("update Contenido set estado_con='f' where id_pub=".$id);
            if($publicacion->sql("update Publicacion set estado_pub='f' where id=".$id))
            {

                Flash::success('Publicación eliminada');
                Router::redirect("/");
            }
          }
          else
          {
              router::Redirect("/");
          }
      }
      else
      {
          router::Redirect("/");
      }
   
    }
    public function editarpub($id,$leng)
    {
    
        if(auth::is_valid())
        {
            if(auth::get("rol_usu") ==  "administrador")
            {
                $this->id_pub = $id; 
                $this->leng= $leng;
                $publicacion = new Publicacion();
                $publicacion->find($id);
                $this->fecha = $publicacion->fecha_pub;
                $this->titulo = $publicacion->titulo_pub;
                $this->detalle = $publicacion->detalle_pub;
            }
            else
            {
                router::Redirect("/");
            }
        }
        else
        {
            router::redirect("/");
        }
        
    }
    public function actualizar()
    {
        if(Auth::is_valid())
        {
            if(auth::get("rol_usu") ==  "administrador")
            {
                if(Input::hasPost('publicacion'))
                    {

                            $publicacion = new Publicacion(Input::post('publicacion'));

                            $fecha = $publicacion->fecha_pub;
                            $titulo = $publicacion->titulo_pub;
                            $detalle = $publicacion->detalle_pub;
                            $id = $publicacion->id;

                            if($publicacion->sql("update Publicacion set fecha_pub='".$fecha."' , titulo_pub='".$titulo."', detalle_pub='".$detalle."' where id=".$id))
                            {
                                Flash::success('Publicación actualizada');
                                Router::redirect("/");
                            }


                    }
            }
            else
            {
                router::redirect("/");
            }
        }
        else
        {
            router::Redirect("/");
        }
    }
    
    public function traducir($id,$leng)
    {
        if(auth::is_valid())
        {
           if(auth::get("rol_usu") == "traductor")
           {
        
                $this->leng = $leng ;
                $this->id_pub = $id;

                $publicacion = new Publicacion();
                $buscar = $publicacion->find($id);
                $this->titulo_es = $buscar->titulo_pub;
                $this->detalle_es = $buscar->detalle_pub;
           }
           else
           {
               router::Redirect("/");
           }
        }
        else
        {
            router::Redirect("/");
        }

       
    }

    
    public function updatepubeng($id,$leng)
    {
        if(auth::is_valid())
        {
            if(auth::get("rol_usu") == "traductor")
            {
                $publicacion = new Publicacion(Input::post('publicacion'));

                $publicacion->detalle_pub_eng = str_replace("'", '', $publicacion->detalle_pub_eng);

                $titulo = $publicacion->titulo_pub_eng;
                $detalle = $publicacion->detalle_pub_eng;


                if($publicacion->sql("update Publicacion set  titulo_pub_eng='".$titulo."', detalle_pub_eng='".$detalle."' where id=".$id))
                {
                    if($leng == "es")
                    {
                        Flash::success('Traducida con exito');
                        Router::redirect("/"); 
                    }
                    else
                    {
                        Flash::success('successful translate');
                        Router::redirect("index/?l=en"); 
                    }

                }
            }
            else
            {
                router::Redirect("/");
            }
        } 
        else
        {
            router::redirect("/");
        }
    }
    public function traduct($leng)
    {
        if(auth::is_valid())
        {
            if(auth::get("rol_usu") ==  "traductor")
            {
                $this->leng = $leng;
                //buscar publicaciones que no tengan traduccion:
                $publicacion = new Publicacion();
                if ($publicacion->count("conditions: estado_pub='t'") == 0) 
                {
                    $this->cont = 0;
                }
                if ($publicacion->count("conditions: estado_pub='t'") > 0) 
                {
                    $this->cont = 1;
                    //buscando comentarios:
                    $arr = $publicacion->find("conditions: estado_pub='t'");
                    $contador = 0;
                    foreach ($arr as $publicacion) 
                    {

                        if($arr[$contador]->titulo_pub_eng == null && $arr[$contador]->detalle_pub_eng == null )
                        {
                            $this->idpub[$contador] =  $arr[$contador]->id;
                            $this->titulo[$contador] = $arr[$contador]->titulo_pub;
                            $this->fecha[$contador]  = $arr[$contador]->fecha_pub;
                            $contador++;

                        }
                        else
                        {

                        }

                    }
                    $this->contador = $contador;

                }
            }
            else
            {
                router::redirect("/");
            }
        }
        else
        {
            router::redirect("/");
        }
    }
    
            
}

