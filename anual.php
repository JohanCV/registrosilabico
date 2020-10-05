<?php
  require_once("config/conexion.php");
  require_once("modelo/Usuario.php");
  require_once("modelo/Asistencia.php");

    if (isset($_GET["value"])) {
        $user_class = new Usuario();
        $asistencia_class = new Asistencia();

        $semanas=date("W");

        //verificacion de si es curso anual_checkbox
        $semanarecorrida = 16;

        $email_md5 = $_GET["value"];
        $email_docente = $user_class->getEmailMd5_login($email_md5); //var_dump($email); die;

        if($email_docente){//echo "entre email"; die();
            $_SESSION['indentificacion'] = $email_docente; //var_dump($_SESSION['indentificacion']);

            $datos_docente = $user_class->getDatosDocente($email_docente); //var_dump($datos_docente); die;
            $datos_docente["semana"]= $semanas - $semanarecorrida;

            //row verifica si existe un registro del registro de asistencia silabico, si existe lo mandamos a temaregistrado.php
            $row = $asistencia_class->get_asistencia_tema_cabecera($datos_docente["facultad"], $datos_docente["escuela"], $datos_docente["asignatura"], $datos_docente["codasig"], $datos_docente["grupo"], $datos_docente["hora_ini"], $datos_docente["hora_fin"], $datos_docente["dia"], $_SESSION['indentificacion']);
            if ($row != 0 and $_SESSION['indentificacion'] == $email_docente) {
              //$_SESSION["idcabeceracontinuo"] = $asistencia_class->getIdCabecera($_SESSION["indentificacion"],$datos_docente["codasig"], $datos_docente["grupo"],$datos_docente["escuela"],$datos_docente["hora_ini"], $datos_docente["hora_fin"]);
              $idoc_yagua = $asistencia_class->getIdCabecera($_SESSION["indentificacion"],$datos_docente["codasig"], $datos_docente["grupo"],$datos_docente["escuela"],$datos_docente["hora_ini"], $datos_docente["hora_fin"]);
              //echo "id_cabecera_dspues_logout: "; var_dump($_SESSION["idcabeceracontinuo"]); die();
              header("Location:".Conectar::ruta()."temaregistrado.php?idoc_ind_yagua=".$idoc_yagua."");
            }
            //row fin

            $temasilabicos[] = $asistencia_class->get_tema_curso_docente_JSON($email_docente,$datos_docente["escuela"],$datos_docente["asignatura"],$datos_docente["grupo"]); //var_dump($temasilabicos); die();
            foreach ($temasilabicos as $value) {
                $contenido= $value[0]["contenido"];
            }

            $temas_json = $asistencia_class->get_tema_JSON($contenido, $datos_docente["semana"]);
            foreach((array)$temas_json as $valor){
                if (is_array((array)$valor->nro_unidad)) {
                    foreach ((array)$valor->capitulos as $value) {
                        if (is_array((array)$value->nro_capitulo)) {
                            foreach ((array)$value->temas as $value2) {
                                if($value2->semana == $datos_docente["semana"]){
                                    $tema_semana = $value2->tema;
                                }
                            }
                        }
                    }
                }
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
               <form method="POST" action="temaregistrado.php">
                  <div class="docente-card text-white mb-3">
                    <div class="card-body">
                      <h3 class="card-title text-center">Registro Silábico</h3>
                        <div class="row h-100 text-center">
                            <div class="col-md-12 my-auto">
                                <table class="table table-sm text-uppercase table-info-asistencia" name="tabla">
                                    <tbody>
                                        <tr>
                                           <th scope="row">Docente</th><input type="hidden" name="docente" value="<?= $datos_docente["nombres"] ?>" />
                                           <td ><b><?= (isset($datos_docente["nombres"])? $datos_docente["nombres"]:"No hay información"); ?></b></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Facultad</th><input type="hidden" name="facultad" value="<?=$datos_docente["facultad"]?>" />
                                          <td><?= (isset($datos_docente["facultad"])? $datos_docente["facultad"]:"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Programa Profesional</th><input type="hidden" name="escuela" value="<?=$datos_docente["escuela"]?>" />
                                          <td><?= (isset($datos_docente["escuela"])? $datos_docente["escuela"] :"No hay información"); ?></td>
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
                                            <th scope="row" >Semana</th><input type="hidden" name="semana" value="<?=$datos_docente["semana"]?>"  />
                                            <td><?php echo (isset($datos_docente["semana"])? $datos_docente["semana"]:"No hay información"); ?>
                                              <b id="anual_checkbox">
                                                  <input type="checkbox" name="activo_anual_chkBox_marcado" value="activo_anual_marcado" id="activo_anual_marcado" checked>
                                                  <label for="yes">Marcar si es Curso Anual</label></b>
                                            </td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Tema de avance</th>
                                          <td>
                                            <div class="block1">
                                              <div class="block2">
                                                <select id="from" name="check_list_tema[]" multiple required>
                                                      <option value="<?= (isset($tema_semana)? $tema_semana:"No hay seleccion de temas :(). Verifique")?>"> <?= (isset($tema_semana)? $tema_semana:"No tiene temas comuniquese con DUFA") ?></option>';

                                                  </select>
                                                </div>
                                              </div>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th scope="row">% Acumulado</th>
                                          <td>
                                            <input name="porcentajeacu" class="form-control" type="number" min="0" max="100" placeholder="Ingrese el Porcentaje Numerico"
                                                   data-toggle='modal' data-target='#modalTemaSilabico' required>
                                          </td>
                                        </tr>
                                        <?php if(!isset($_POST["enviar"]) ): ?>
                                            <tr>
                                              <th scope="row"></th>
                                              <td>
                                                <input type="hidden" class="form-control" name="enviar" value="guardadoCabe">
                                                <button type="submit" class="btn btn-success-own col-md-12" name="guardarCabTema" id="saveCab" style="margin-top:15px;">
                                                        <i class="fas fa-fw fa-save" id="guardaravance"></i> Guardar Avance
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
    //no hay value o el correo cifrado
    header("Location:".Conectar::ruta()."mensaje.php?op=1");
  }
?>
