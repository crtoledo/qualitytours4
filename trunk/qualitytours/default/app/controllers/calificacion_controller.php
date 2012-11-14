<?php
load::model('calificacion');
class CalificacionController extends AppController 
{
    public function index()
    {
        
    }
    
    public function captura($votacion)
    {

      echo  flash::info($votacion);     
        
    }
}
?>
