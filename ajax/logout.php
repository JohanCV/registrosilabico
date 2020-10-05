<?php
    require_once("./config/conexion.php");

    if( isset($_SESSION["indentificacion"])){ //var_dump($_SESSION["indentificacion"]);
        //echo "Estoy dentro el logout";

        unset($_SESSION["indentificacion"]);
        unset($_SESSION["id_cabecera"]);
        unset($_SESSION["idcabeceracontinuo"]);

        session_destroy();
        header("Location:https://aulavirtual.unsa.edu.pe/aulavirtual");
    }
?>
