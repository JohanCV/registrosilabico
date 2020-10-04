<?php
  //if ($_POST["btnEditarTema"]) { echo "estoy dentro ajax/btnEditarTema"; var_dump($_POST); echo "</br>";
  if ($_GET["id_docente"]) {
    $idtemaregistrado = $_GET["id_docente"]; var_dump($_GET["id_docente"]); //die();
    $datos_editar_tema = $asistencia_class->get_datos_asistencia_tema_cabecera_registrado($_SESSION["indentificacion"],$_SESSION["idcabeceracontinuo"],$_POST["facultad"], $_POST["escuela"], $_POST["asignatura"], $_POST["codasig"], $_POST["grupo"], $_POST["hora_inicial"]);
    echo "</br>"."Datos_editar_tema:  "; var_dump($datos_editar_tema);
    $emailmd5_editar = $_SESSION["indentificacion"];        echo "</br>"."indentificacion:  ";   var_dump($_SESSION["indentificacion"]); echo "</br>";
    //$idtemaregistrado = $_SESSION["idcabeceracontinuo"];    echo "idCab: "; var_dump($_SESSION["idcabeceracontinuo"]); //die();

    if (isset($_SESSION["indentificacion"]) && !empty($_SESSION["indentificacion"])){
      header("Location:".Conectar::ruta()."editartema.php?value=".md5($emailmd5_editar)."&idocedic=".$idtemaregistrado."");
    }else {
      echo "no inicio correctamente edtr";
    }
  }
?>
