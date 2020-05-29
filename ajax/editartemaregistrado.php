<?php
  if ($_POST["btnEditarTema"]) { echo "estoy dentro ajax/btnEditarTema";
    $emailmd5_editar = $_SESSION["correo"];           //var_dump($_SESSION["correo"]);
    $idtemaregistrado = $_SESSION["id_cabecera"];     //var_dump($_SESSION["id_cabecera"]);
    header("Location:".Conectar::ruta()."editartema.php?value=".md5($emailmd5_editar)."&idoc=".$idtemaregistrado."");
  }
?>
