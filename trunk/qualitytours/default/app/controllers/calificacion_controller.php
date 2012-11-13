<?php
load::model('calificacion');
class CalificacionController extends AppController 
{
    public function index()
    {
        
    }
    
    public function captura()
    {
         
        
        if(Input::hasPost('calificacion'))
        {
            $calificacion = new Calificacion(Input::post('calificacion'));
            
            if($calificacion->save())
            {
                
            }
          
        }
    }
}
?>
