<?php 
    class Conexion{
        private $servidor="localhost";
        private $usuario="root";
        private $bd="segurosexpress";
        private $password="";
        public function conectar(){
            $conexion = mysqli_connect($this->servidor, $this->usuario, $this->password, $this->bd) or die(mysqli_error($conexion));
            mysqli_query("SET NAMES 'utf8'", $conexion); 
            mysqli_query("SET time_zone = '-06:00';", $conexion); 
            if(!mysqli_select_db($this->$bd)){
                echo "<br/>Error: no se pudo conectar a la BD, revisa los datos de conexion.";
                exit;
            }
            return $conexion;
        }
        
        function Desconectar() {
            if (gettype($conexion) == "resource") {
                mysqli_close($conexion);
            }
        }

        function Ejecutar($query) {
            $resultado = mysqli_query($query);        
            if (!$resultado) {
                $resultado = mysqli_error();
            }        
            return $resultado;
        }
    }
?>