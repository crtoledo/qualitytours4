<?php

    require_once 'connection.php';
    $Db = new db();
    $conn = $Db->open();
    
    $comentario = pg_query('SELECT * FROM comentario')
    or die(pg_errormessage());
    if(!$comentario)
    {
        pg_close();
        echo json_encode('Hubo un error ejecutando el QUERY: '. pg_errormessage());
    }
    elseif(!pg_num_rows($comentario))
    {
        pg_close();
        echo json_encode('No hay data disponible.');
    } 
    else 
    {
    $header = false;
    $output_string = '';
    $output_string .=  '<table border="1">\n';
    while($row = mysql_fetch_assoc($comentario))
    {
        if(!$header)
        {
            $output_string .= '<tr>\n';
            foreach($row as $header => $value)
            {
                $output_string .= '<th>{$header}</th>\n';
            }
            $output_string .= '</tr>\n';
        }
        $output_string .= '<tr>\n';
        foreach($row as $value)
        {
            $output_string .= '<th>{$value}</th>\n';
        }
        $output_string .= '</tr>\n';
    }
    $output_string .= '</table>\n';
} 
pg_close();
// El siguiente echo es para devolver el resultado
echo json_encode($output_string); 
?>
