<?php
  require_once("config/conexion.php");
  require_once("modelo/Asistencia.php");
  $asistencia_class = new Asistencia();
  $correo = $_SESSION["indentificacion"];

  // $id_docente =(!empty($_GET["idocgt"])? $_GET["idocgt"]: "no definido");                        echo "id_docente_nuevo"; var_dump($id_docente);  echo "</br>";
  // $id_docente_yasave = (!empty($_GET["idoc_ind_yagua"])? $_GET["idoc_ind_yagua"]: "no definido"); echo "idoc_ind_yaguaardado: ";var_dump($id_docente_yasave); echo "</br>";
  // $iddocupdate =  (!empty($_GET["iddocupdate"])? $_GET["iddocupdate"]: "no definido");             echo "idoc_ind_update: ";var_dump($iddocupdate); echo "</br>";

  $id_docente =$_GET["idocgt"];                     //echo "id_docente_nuevo";      var_dump($id_docente);  echo "</br>";
  $id_docente_yasave = $_GET["idoc_ind_yagua"];     //echo "idoc_ind_yaguaardado: ";var_dump($id_docente_yasave); echo "</br>";
  $iddocupdate =  $_GET["iddocupdate"];             //echo "idoc_ind_update: ";     var_dump($iddocupdate); echo "</br>";

  $facu = (isset($_POST["facultad"])? $_POST["facultad"]:"no definido");    //var_dump($_POST["facultad"]); echo "</br>";
  $escu = (isset($_POST["escuela"])? $_POST["escuela"]:"no definido");      //var_dump($_POST["escuela"]); echo "</br>";
  $asig = (isset($_POST["asignatura"])? $_POST["asignatura"]:"no definido");//var_dump($_POST["asignatura"]); echo "</br>";
  $codasig = (isset( $_POST["codasig"])? $_POST["codasig"]:"no definido");                                             //var_dump($_POST["codasig"]); echo "</br>";
  $grupo   = (isset($_POST["grupo"])?$_POST["grupo"]:"no definido");                                                 //var_dump($_POST["grupo"]); echo "</br>";
  $horaini = (isset($_POST["hora_inicial"])?$_POST["hora_inicial"]:"no definido");                                        //var_dump($_POST["hora_inicial"]); echo "</br>";

  $url_accion = "";
  if (isset($correo) && !empty($correo) && isset($facu) && !empty($facu) && isset($escu) && !empty($escu) && isset($asig) && !empty($asig)
      && isset($codasig) && !empty($codasig) && isset($grupo) && !empty($grupo) && isset($horaini) && !empty($horaini)) {

      //if para asignar el valor que le corresponde de los id
      if (!empty($id_docente)) {
        $porcentaje_editar = $asistencia_class->get_datos_asistencia_tema_cabecera_registrado($correo,$id_docente,$facu, $escu, $asig,$codasig,$grupo,$horaini);
      }
      if (!empty($id_docente_yasave)) {
        $porcentaje_editar = $asistencia_class->get_datos_asistencia_tema_cabecera_registrado($correo,$id_docente_yasave,$facu, $escu, $asig,$codasig,$grupo,$horaini);
      }
      if (!empty($iddocupdate)) {
        $porcentaje_editar = $asistencia_class->get_datos_asistencia_tema_cabecera_registrado($correo,$iddocupdate,$facu, $escu, $asig,$codasig,$grupo,$horaini);
      }//echo "porcentaje: ";var_dump($porcentaje_editar); echo "</br>";
      if ($porcentaje_editar) {
          foreach ($porcentaje_editar as $showporcentaje_editar) {
                $porcentaje_editarn = $showporcentaje_editar["porcentaje"]; //var_dump($porcentaje_editarn);
          }
      }
  }else {
     echo "los datos estan vacios post"."</br>";
  }
  if(isset($correo) && !empty($correo)){
        //guardamos la informacion que viene del submit guardarCabTema
        if (isset($_POST["guardarCabTema"])) {
            $url_accion = "temaregistrado.php?idtemasave=".$id_docente."";
            require_once('ajax/guardartemacab.php');
        }
        if (isset(($_POST["btnEditarTema"]))) {
              //hacer un if para asignar el valor que le corresponde de los id
              if (!empty($id_docente)) {
                $idtemaregistrado =$id_docente; //var_dump($id_docente);
              }
              if (!empty($id_docente_yasave)) {
                $idtemaregistrado =$id_docente_yasave; //var_dump($id_docente_yasave);
              }
              if (!empty($iddocupdate)) {
                $idtemaregistrado =$iddocupdate; //var_dump($iddocupdate);
              }
              $emailmd5_editar = $_SESSION["indentificacion"];
              if (isset($_SESSION["indentificacion"]) && !empty($_SESSION["indentificacion"])){
                header("Location:".Conectar::ruta()."editartema.php?value=".md5($emailmd5_editar)."&idoc=".$idtemaregistrado."");
              }else {
                echo "no inicio correctamente edtr";
              }
        }
        if (isset($_POST["actualizarCabTema"])) {
            if (isset($_SESSION["indentificacion"]) && !empty($_SESSION["indentificacion"])){
              if (!empty($id_docente)) {
                $idtemaupdate =$id_docente; //var_dump($id_docente);
              }
              if (!empty($id_docente_yasave)) {
                $idtemaupdate =$id_docente_yasave; //var_dump($id_docente_yasave);
              }
              if (!empty($iddocupdate)) {
                $idtemaupdate =$iddocupdate; //var_dump($iddocupdate);
              }
              //var_dump($_POST["check_list_tema"]);
              //var_dump($_POST["porcentajeacu"]);
              $bool_update = $asistencia_class->actualizarTemaRegistrado($_POST["check_list_tema"],$_POST["porcentajeacu"],$idtemaupdate); echo "</br>";
              //echo "bool_update. :  ";var_dump($bool_update);
            }else {
              echo "no inicio correctamente edtr";
            }
        }
        if (isset($_POST["btnTerminarTema"])) {
            require_once('ajax/logout.php');
        }
      require_once("vista/cabecera.php");
 ?>
<body class="bg-gradient-primary login-body">

 <div class="login-container">

   <!-- Outer Row -->
   <div class="row justify-content-center">

     <div class="col-xl-10 col-lg-12 col-md-9">

       <div class="login-card o-hidden border-0 shadow-lg my-5">
         <div class="card-body p-0">
           <!-- Nested Row within Card Body -->
           <div class="row">
             <!-- informacion derecha -->
             <div class="col-lg-12 text-white">
               <form method="POST" action="<?=$url_accion?>">
                  <div class="docente-card text-white mb-3">
                    <div class="card-body">
                      <h3 class="card-title text-center">Registro Silábico</h3>
                        <div class="row h-100 text-center">
                            <div class="col-md-12 my-auto">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <?php if(isset($bool_update) && $bool_update == true): ?>
                                        <form method="post">
                                            <div class="modal-body mx-3">
                                                <h5>Su Tema Silabico <strong> fue actulizado exitosamente</strong>.</h5>
                                                <!--h5>Su Tema Silabico fue registrado con un avance del <strong> <(isset($porcentaje_editarn)?$porcentaje_editarn:"0") ?> % </strong> exitosamente.</h5-->
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                                  <input type="hidden" name="iddocen" value="<?=$id_docente?>" />
                                                   <button type="submit" id="btnEditarTema" name='btnEditarTema'
                                                           value="Editar Tema" class="btn btn-warning" style="color: #fff;">Editar
                                                   </button>
                                                   <button type="submit" id="btnTerminarTema" name='btnTerminarTema'
                                                           value="Terminar Tema" class="btn btn-danger">Cerrar Sesion
                                                   </button>

                                            </div>
                                        </form>
                                      <?php elseif (isset($_SESSION['estadoRegistroCab']) && $_SESSION['estadoRegistroCab'] == true) :?>

                                            <div class="modal-body mx-3">
                                                <h5>Su Tema Silabico <strong> fue registrado exitosamente.</strong></h5>
                                                <!--h5>Su Tema Silabico fue registrado con un avance del <strong>(isset($porcentaje_editarn)?$porcentaje_editarn:"0") ?> % </strong> exitosamente.</h5-->
                                                <input type="hidden" name="iddocen" value="<?=$id_docente?>" />
                                                 <button type="submit" id="btnEditarTema" name='btnEditarTema'
                                                         value="Editar Tema" class="btn btn-warning" style="color: #fff;">Editar
                                                 </button>
                                                 <button type="submit" id="btnTerminarTema" name='btnTerminarTema'
                                                         value="Terminar Tema" class="btn btn-danger">Cerrar Sesion
                                                 </button>
                                            </div>
                                            <?php else:?>
                                                  <div class="modal-body mx-3">
                                                      <h5>Su Tema Silabico <strong> fue registrado exitosamente.</strong></h5>
                                                      <!--h5>Su Tema Silabico fue registrado con un avance del <strong>(isset($porcentaje_editarn)?$porcentaje_editarn:"0") ?> % </strong> exitosamente.</h5-->
                                                      <input type="hidden" name="iddocen" value="<?=$id_docente?>" />
                                                       <button type="submit" id="btnEditarTema" name='btnEditarTema'
                                                               value="Editar Tema" class="btn btn-warning" style="color: #fff;">Editar
                                                       </button>
                                                       <button type="submit" id="btnTerminarTema" name='btnTerminarTema'
                                                               value="Terminar Tema" class="btn btn-danger">Cerrar Sesion
                                                       </button>
                                                  </div>

                                      <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>

<?php
  require_once("vista/footer.php");

}else{
  //header("Location:".Conectar::ruta_aulavirtual());
  echo "No hay ninguna variable para guardar, comuniquese con el Administrador";
  exit();
}
 ?>
