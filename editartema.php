<?php
  require_once("config/conexion.php");
  require_once("modelo/Usuario.php");
  require_once("modelo/Asistencia.php");

  $user_class = new Usuario();
  $asistencia_class = new Asistencia();

  $semanas=date("W");

  if (isset($_GET["value"]) AND !empty($_GET["value"]) AND isset($_GET["idoc"]) AND  !empty($_GET["idoc"])) {

      $email_md5 = $_GET["value"];
      $idoc = $_GET["idoc"];          //var_dump($_GET["idoc"]);
      $url_accion = "temaregistrado.php?iddocupdate=".$idoc."";
      //capturo el email de quien inicia sesion
      $email = $user_class->getEmailMd5_login($email_md5);
      if($email){ //echo "$email <br/>"; var_dump($email);
          $datos_docente = $user_class->getDatosDocente($email);
          $datos_docente["semana"]= $semanas -36;

          $facu = $datos_docente["facultad"];
          $escu = $datos_docente["escuela"];
          $asig = $datos_docente["asignatura"];
          $codasig = $datos_docente["codasig"];
          $grupo = $datos_docente["grupo"];
          $horaini = $datos_docente["hora_ini"];
          $horafin = $datos_docente["hora_fin"];
          //start verificacion de idoc existe en la bd,si no existe mandarle un mensaje
          $bool_idoc = $asistencia_class->getIdCabecera($_SESSION["indentificacion"],$datos_docente["codasig"], $datos_docente["grupo"],$datos_docente["escuela"],$datos_docente["hora_ini"], $datos_docente["hora_fin"]);
          //var_dump($bool_idoc);
          if ($bool_idoc == null) {
              header("Location:".Conectar::ruta()."mensaje.php?op=5");
          }
          //end verificacion

          if (isset($email) && !empty($email) && isset($facu) && !empty($facu) && isset($escu) && !empty($escu) && isset($asig) && !empty($asig)
              && isset($codasig) && !empty($codasig) && isset($grupo) && !empty($grupo) && isset($horaini) && !empty($horaini)) {

              $porcentaje_editar = $asistencia_class->get_datos_asistencia_tema_cabecera_registrado($email,$idoc,$datos_docente["facultad"], $datos_docente["escuela"], $datos_docente["asignatura"], $datos_docente["codasig"], $datos_docente["grupo"], $datos_docente["hora_ini"]);
              //var_dump($porcentaje_editar);

              if (isset($porcentaje_editar)) {
                  foreach ($porcentaje_editar as $showporcentaje_editar) {
                        $nombre = $showporcentaje_editar["nombres"];
                        $facultad = $showporcentaje_editar["facultad"];
                        $escuela= $showporcentaje_editar["programa"];
                        $asignatura = $showporcentaje_editar["asignatura"];
                        $codasig = $showporcentaje_editar["codasig"];
                        $grupo = $showporcentaje_editar["grupo"];
                        $horainicial = $showporcentaje_editar["hora_ini"];
                        $horafinal = $showporcentaje_editar["hora_fin"];
                        $dia = $showporcentaje_editar["dia"];
                        $fecha_registro = $showporcentaje_editar["fecha"];
                        $semana = $showporcentaje_editar["semana"];
                        $porcentaje_editarn = $showporcentaje_editar["porcentaje"]; //var_dump($porcentaje_editarn);
                        $tema_editarn["tema"] =  $showporcentaje_editar["tema"];
                  }
              }//var_dump($nombre);
          }else {
              echo "estan mal los datos para obtener el tema y porcentaje ";
          }
      }else {
        //echo "email es igual false: no existe el email en nuestra base de datos busque a DUFA";
          header("Location:".Conectar::ruta()."mensaje.php?op=3");
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
                                <table class="table table-sm text-uppercase table-info-asistencia">
                                    <tbody>
                                        <tr>
                                           <th scope="row">Docente</th><input type="hidden" name="docente" value="<?= $datos_docente["nombres"] ?>" />
                                           <td><b><?= (isset($datos_docente["nombres"])? $datos_docente["nombres"]:"No hay información"); ?></b></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Facultad</th><input type="hidden" name="facultad" value="<?=$datos_docente["facultad"]?>" />
                                          <td><?= (isset($datos_docente["facultad"])? $datos_docente["facultad"]:"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Programa Profesional</th><input type="hidden" name="escuela" value="<?=$datos_docente["escuela"]?>" />
                                          <td><?=(isset($datos_docente["escuela"])? $datos_docente["escuela"] :"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Curso</th><input type="hidden" name="asignatura" value="<?=$datos_docente["asignatura"]?>" />
                                          <td class="h5"><b><?= (isset($datos_docente["asignatura"])? $datos_docente["asignatura"]:"No hay información"); ?></b></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Código</th><input type="hidden" name="codasig" value="<?=$datos_docente["codasig"]?>" />
                                          <td><?= (isset($datos_docente["codasig"])? $datos_docente["codasig"]:"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Grupo</th><input type="hidden" name="grupo" value="<?=$datos_docente["grupo"]?>"  />
                                                                    <input type="hidden" name="aula" value="<?=$datos_docente["aula"]?>"  />
                                                                    <input type="hidden" name="hora_inicial" value="<?=$datos_docente["hora_ini"]?>"  />
                                                                    <input type="hidden" name="hora_final" value="<?=$datos_docente["hora_fin"]?>"  />
                                                                    <input type="hidden" name="dia" value="<?=$datos_docente["dia"]?>"  />
                                                                    <input type="hidden" name="correo_docente" value="<?=$email_docente?>"  />
                                          <td><?= (isset($datos_docente["grupo"])? $datos_docente["grupo"]:"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Semana</th><input type="hidden" name="semana" value="<?=$datos_docente["semana"]?>"  />
                                          <td><?php echo (isset($semana)? $semana:"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Tema de avance</th>
                                          <td>
                                                  <select name="check_list_tema[]" multiple required>
                                                                  <option value="<?= (isset($tema_editarn["tema"])? $tema_editarn["tema"]:"No hay seleccion de temas. Verifique")?>"> <?= (isset($tema_editarn["tema"])? $tema_editarn["tema"]:"No hay seleccion de temas") ?></option>';
                                                                  <option style="font-size: 1%; background-color: #858796;" disabled>&nbsp;</option>
                                                  </select>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th scope="row">% Acumulado</th>
                                          <td>
                                            <input name="porcentajeacu" class="form-control" type="number" min="0" max="100" placeholder="<?= (isset($porcentaje_editarn)?$porcentaje_editarn:"No hay informacion") ?>"  required>
                                          </td>
                                        </tr>
                                        <?php if(!isset($_POST["enviar"]) ): ?>
                                            <tr>
                                              <th scope="row"></th>
                                              <td>
                                                <input type="hidden" class="form-control" name="enviaractualizaciontema" value="actualizadoCabetema">
                                                <button type="submit" class="btn btn-warning col-md-12" name="actualizarCabTema" id="actualizarCabTema" style="margin-top:15px;">
                                                        <i class="fas fa-fw fa-save" id="guardaravance"></i> Actualizar Avance
                                                </button>
                                              </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
  //exit();
  echo "no coloque datos que no corresponden";
}
?>
