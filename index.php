<?php
  require_once("config/conexion.php");
  require_once("modelo/Usuario.php");
  require_once("modelo/Asistencia.php");

  $user_class = new Usuario();
  $asistencia_class = new Asistencia();
  $row = $asistencia_class->get_asistencia_tema_cabecera();
  //echo "$row";
  if ($row != 0) {
      header("Location:".Conectar::ruta()."temaregistrado.php");
  }
  if (isset($_GET["value"])) {
      $email_md5 = $_GET["value"];
      //echo "$emailmd5";
      //echo "<br/> PROBANDO";
      //capturo el email de quien inicia sesion
      $email = $user_class->getEmailMd5($email_md5);
      if($email != "nomatch"){
          //echo "$email <br/>";
          $user_class->getDatosDocente($email);
          $temasilabico[] = $asistencia_class->get_tema_curso_docente($email);
          $_SESSION["correo"] = $email;

          (isset($_SESSION["porcentaje"])?$porcentaje = $_SESSION["porcentaje"]: $porcentaje = $_SESSION["semana"]*100/17); //echo $porcentaje;
          (isset($rpta)? $rpta = 0 : $rpta = number_format($porcentaje));

          //echo $_SESSION["semana"]."<br/>";
          //echo $_SESSION["porcentaje"];
          //var_dump($temasilabico);
          //echo count($temasilabico);
          //var_dump($_SESSION["row_cnt_temas_cap"] );
      }else {
          //header("Location:".Conectar::ruta_aulavirtual());
          header("Location:".Conectar::ruta()."mensaje.php?op=3");
          //echo "email es igual nomatch: no existe el email en nuestra base de datos";
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
                                <table class="table table-sm text-uppercase table-info-asistencia">
                                    <tbody>
                                        <tr>
                                           <th scope="row">Docente</th>
                                           <td><b><?= (isset($_SESSION["nombreCab"])? $_SESSION["nombreCab"]:"No hay información"); ?></b></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Facultad</th>
                                          <td><?= (isset($_SESSION["facultadCab"])? $_SESSION["facultadCab"]:"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Programa Profesional</th>
                                          <td><?= (isset($_SESSION["escuelaCab"])? $_SESSION["escuelaCab"] :"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Curso</th>
                                          <td class="h5"><b><?= (isset($_SESSION["asignaturaCab"])? $_SESSION["asignaturaCab"]:"No hay información"); ?></b></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Código</th>
                                          <td><?= (isset($_SESSION["codasignaturaCAb"])? $_SESSION["codasignaturaCAb"]:"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Grupo</th>
                                          <td><?= (isset($_SESSION["grupo"])? $_SESSION["grupo"]:"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Semana</th>
                                          <td><?php $semanas=date("W"); $_SESSION["semana"]= $semanas -16;  echo (isset($_SESSION["semana"])? $_SESSION["semana"]:"No hay información"); ?></td>
                                        </tr>
                                        <tr>
                                          <th scope="row">Tema de avance</th>
                                          <td>
                                                <select name="check_list_tema[]" multiple required>
                                                      <?php for($i = 0; $i < $_SESSION["row_cnt_temas_cap"]; $i++){
                                                              foreach ($temasilabico as $showtemasilabico) { ?>
                                                                  <option value="<?= (isset($showtemasilabico[$i]["tema"])? $showtemasilabico[$i]["tema"]:"No hay seleccion de temas. Verifique")?>"> <?= (isset($showtemasilabico[$i]["tema"])? $showtemasilabico[$i]["tema"]:"No hay seleccion de temas") ?></option>';
                                                                  <option style="font-size: 1%; background-color: #858796;" disabled>&nbsp;</option>
                                                      <?php   }
                                                            }?>
                                                  </select>
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
                                                <button type="submit" class="btn btn-success col-md-12" name="guardarCabTema" id="saveCab" style="margin-top:15px;">
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
  header("Location:".Conectar::ruta_aulavirtual());
  exit();
}
?>
