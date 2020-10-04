<?php
    require_once("./config/conexion.php");

    if( isset($_SESSION["indentificacion"])){ //var_dump($_SESSION["indentificacion"]);
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
        unset($_SESSION["indentificacion"]);
        unset($_SESSION["id_cabecera"]);//var_dump($_SESSION["id_cabecera"]);
        unset($_SESSION["idcabeceracontinuo"]);

        session_destroy();
        // header("Location:".Conectar::ruta_aulavirtual());
        header("Location:https://aulavirtual.unsa.edu.pe/aulavirtual");
    }
?>
