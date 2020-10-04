<?php
    require_once("modelo/Asistencia.php");
    //echo "estoy dentro el archivo guardarCabTema <br/>";
    $asistencia = new Asistencia();
    $row = $asistencia->get_asistencia_tema_cabecera($_POST["facultad"], $_POST["escuela"], $_POST["asignatura"], $_POST["codasig"], $_POST["grupo"], $_POST["hora_inicial"], $_POST["hora_final"], $_POST["dia"], $_POST["correo_docente"]);

    if(isset($_POST["actualizarCabTema"])){ //echo "entro al post del botn guardarCabTema <br/>";
        //$row =0;
        $asis = $asistencia->actualizarTemaRegistrado($_POST["check_list_tema"],$_POST["porcentajeacu"]);
    }

?>
