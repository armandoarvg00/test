<?php 
require_once("Conexion.php");
class catalogos{
    private $tabla;
    public function ejecutar_consulta($consulta){
        $conexion =new $Conexion();
        $conexion->conectar();
        $query = $conexion->ejecutar($consulta);
        $conexion->Desconectar();
        return $query;
    }
    function satinizar_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = str_replace("'", "´", $data);
        return $data;
    }
    /*function enviarCorreo($subject, $correos, $message, $pintar_mensaje) {
        $mail = new Mail();
        $parametroGlobal = new ParametroGlobal();
        if (isset($this->empresa)) {
            $parametroGlobal->setEmpresa($this->empresa);
        }
        if ($parametroGlobal->getRegistroById("10")) {
           // $mail->setFrom($parametroGlobal->getValor());
        } else {
           // $mail->setFrom("notificador@administro.com.mx");
        }
        $mail->setSubject($subject);
        $mail->setBody($message);
        foreach ($correos as $value) {*/
           /* if (isset($value) && $value != "" && filter_var($value, FILTER_VALIDATE_EMAIL)) {*//* Si el correo es valido */
              /*  $mail->setTo($value);
                if ($mail->enviarMail() == "1") {
                    if ($pintar_mensaje) {
                        echo "<br/>Un correo fue enviado a $value.";
                    }
                } else {
                    if ($pintar_mensaje) {
                        echo "<br/>Error: No se pudo enviar el correo a $value";
                    }
                }
            }
        }
    }*/
}
?>