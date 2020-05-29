<?php
    require_once("./config/conexion.php");

    if( isset($_SESSION["nombreCab"]) and isset($_SESSION["facultadCab"]) and isset($_SESSION["escuelaCab"]) AND
        isset($_SESSION["asignaturaCab"]) and isset($_SESSION["codasignaturaCAb"]) and isset($_SESSION["grupo"]) AND
        isset($_SESSION["aula"]) and isset($_SESSION["semana"]) ){
        //echo "Estoy dentro el logout";
        // unset($_SESSION["nombreCab"]);
        // unset($_SESSION["facultadCab"]);
        // unset($_SESSION["escuelaCab"]);
        // unset($_SESSION["asignaturaCab"] );
        // unset($_SESSION["codasignaturaCAb"] );
        // unset($_SESSION["grupo"]);
        // unset($_SESSION["hora_inicial"]);
        // unset($_SESSION["hora_final"]);
        // unset($_SESSION["dia"]);
        // unset($_SESSION["aula"] );
        // unset($_SESSION["semana"]);
        // unset($_SESSION["correo"]);
        // unset($_SESSION["id_cabecera"]);

        //session_destroy();
        header("Location:".Conectar::ruta_aulavirtual());
        exit();
    }
?>
