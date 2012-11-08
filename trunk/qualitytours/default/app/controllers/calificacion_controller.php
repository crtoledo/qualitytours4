<?php
load::model('calificacion');
class CalificacionController extends AppController 
{
    public function index()
    {
        
    }
    
    public function captura($leng)
    {
         
        
        if(Input::hasPost('calificacion'))
        {
            $calificacion = new Calificacion(Input::post('calificacion'));
            
            if($calificacion->save())
            {
                if($leng == "es")
                {
                    Flash::error('Error al agregar');
                }
                else
                {
                    Flash::error('user add error');
                }
            }
          
        }
    }
}
?>
