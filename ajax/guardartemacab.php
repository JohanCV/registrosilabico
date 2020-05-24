<?php
    require_once("modelo/Asistencia.php");
    //echo "estoy dentro el archivo guardarCabTema <br/>";
    $asistencia = new Asistencia();
    $row = $asistencia->get_asistencia_tema_cabecera();
    //var_dump($row);
    //echo $row."<br/>";
    if(isset($_POST["guardarCabTema"])){ //echo "entro al post del botn guardarCabTema <br/>";
        //$row =0;
        $asis = $asistencia->registrar_asistencia_tema_cabecera($row,$_SESSION["nombreCab"],$_SESSION["facultadCab"],$_SESSION["escuelaCab"],$_SESSION["asignaturaCab"],
                                                                $_SESSION["codasignaturaCAb"],$_SESSION["grupo"],$_SESSION["hora_inicial"],$_SESSION["hora_final"],
                                                                $_SESSION["dia"], $_SESSION["correo"],$_SESSION["aula"],$_SESSION["semana"],$_POST["check_list_tema"],$_SESSION["porcentajeacu"]);
        var_dump($_SESSION['estadoRegistroCab']);
        if(isset($_SESSION['estadoRegistroCab']) && $_SESSION['estadoRegistroCab'] == true){echo "entre a estadoRegistroCab<br/>";
            $_SESSION["id_cabecera"] = $asistencia->getIdCabecera($_SESSION["correo"],$_SESSION["codasignaturaCAb"],$_SESSION["grupo"],$_SESSION["escuelaCab"]);
            echo "id_cabecera: "; var_dump($_SESSION["id_cabecera"]);
        }
    }else{ //echo "no existel post <br/>";
        if($row != 0){
            $_SESSION["idcabeceracontinuo"]= $asistencia->getIdCabecera($_SESSION["correo"],$_SESSION["codasignaturaCAb"],$_SESSION["grupo"],$_SESSION["escuelaCab"]);
            //echo "id_cabecera: "; var_dump($_SESSION["id_cabecera"]);
        }
    }

?>
