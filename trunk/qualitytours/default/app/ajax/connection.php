<?php

class db
{
    public function open()
    {
        $connection_string="host='" . addslashes('localhost')
        . "' user='" . addslashes('postgres')
        . "' password='" . addslashes('qt123123')
        . "' dbname='" . addslashes("qualitytours") . "'";
        return pg_connect($connection_string);
    }
    
    public function close($conn)
    {
        pg_close($conn);
    }
}
?>
