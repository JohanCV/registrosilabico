<?php
    require_once("modelo/Asistencia.php");
    //echo "estoy dentro el archivo guardarCabTema <br/>";
    $asistencia = new Asistencia();
    $row = $asistencia->get_asistencia_tema_cabecera();
    //var_dump($row);
    //echo $row."<br/>";
    if(isset($_POST["actualizarCabTema"])){ //echo "entro al post del botn guardarCabTema <br/>";
        //$row =0;
        $asis = $asistencia->actualizarTemaRegistrado($_POST["check_list_tema"],$_POST["porcentajeacu"]);
        //var_dump($_SESSION['estadoRegistroCab']);
        // if(isset($_SESSION['estadoRegistroCab']) && $_SESSION['estadoRegistroCab'] == true){//echo "entre a estadoRegistroCab<br/>";
        //     $_SESSION["id_cabecera"] = $asistencia->getIdCabecera($_SESSION["correo"],$_SESSION["codasignaturaCAb"],$_SESSION["grupo"],$_SESSION["escuelaCab"]);
        //     //echo "id_cabecera: "; var_dump($_SESSION["id_cabecera"]);
        // }
    }else{ //echo "no existel post <br/>";
        if($row != 0){
            $_SESSION["idcabeceracontinuo"]= $asistencia->getIdCabecera($_SESSION["correo"],$_SESSION["codasignaturaCAb"],$_SESSION["grupo"],$_SESSION["escuelaCab"]);
            //echo "id_cabecera: "; var_dump($_SESSION["id_cabecera"]);
        }
    }

?>
