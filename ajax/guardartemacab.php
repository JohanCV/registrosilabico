<?php
    require_once("modelo/Asistencia.php");
    //echo "estoy dentro el archivo guardarCabTema <br/>";
    $asistencia = new Asistencia();

    $row = $asistencia->get_asistencia_tema_cabecera($_POST["facultad"], $_POST["escuela"], $_POST["asignatura"], $_POST["codasig"], $_POST["grupo"], $_POST["hora_inicial"], $_POST["hora_final"], $_POST["dia"], $_POST["correo_docente"]);
    echo "Si ya existe un registro guardado row= ".$row."<br/>";
    if($row == 0){ //echo "entro al post del botn guardarCabTema <br/>"; var_dump($_POST); die();
        //$row =0;
        $bool_save = $asistencia->registrar_asistencia_tema_cabecera($row,$_POST["docente"],$_POST["facultad"],$_POST["escuela"],$_POST["asignatura"],
                                                                $_POST["codasig"],$_POST["grupo"],$_POST["hora_inicial"],$_POST["hora_final"],
                                                                $_POST["dia"], $_POST["correo_docente"],$_POST["aula"],$_POST["semana"],$_POST["check_list_tema"],$_POST["porcentajeacu"]);
        //echo "bool_save:  ";var_dump($bool_save); die();
        if(isset($_SESSION['estadoRegistroCab']) && $_SESSION['estadoRegistroCab'] == true){//echo "entre a estadoRegistroCab<br/>";
            $idtemaregistrado = $asistencia->getIdCabecera($_POST["correo_docente"],$_POST["codasig"],$_POST["grupo"],$_POST["escuela"],$_POST["hora_inicial"],$_POST["hora_final"]);
            header("Location:".Conectar::ruta()."temaregistrado.php?idocgt=".$idtemaregistrado."");
        }
    }

?>
