
<?php
    class conectar{
        public static function conexion(){
            $con = new mysqli ('localhost', 'root', '', 'empleo');
            $con->set_charset('utf8');

            return $con;
        }
    }
?>