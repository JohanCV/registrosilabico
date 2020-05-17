<?php
  global $DB, $CFG, $USER;

  require_once("config/conexion.php");
  require_once("modelo/Usuario.php");

  setlocale(LC_ALL,"es_ES");

  if(isset($_POST["username"])){
    $usuario = new Usuario();
    $resul = $usuario->getCabeceraAsistencia();

      if(isset($_POST["guardarCab"])){
        require_once('ajax/guardarasiscab.php');
      }

      require_once("config/asis.php");

      $porcentaje = $_SESSION["semana"]*100/17 ;
      $rpta = 24;
      $rpta = number_format($porcentaje);
?>
<form method="POST" action="asistencias.php">
          <div class="docente-card text-white mb-3">
            <div class="card-body">
              <h3 class="card-title text-center" style="color: #000;">Docente </h3>
                <div class="row h-100 text-center">
                    <div class="col-md-12 my-auto">
                        <table class="table table-sm text-uppercase table-info-asistencia">
                            <tbody>
                                <tr>
                                  <th scope="row">Docente</th>
                                   <td><b><?= $_SESSION["nombre"] ?></b></td>
                                </tr>
                                <tr>
                                  <th scope="row">Facultad</th>
                                  <td><?= $_SESSION["facultadCab"] ?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Programa Profesional</th>
                                  <td><?= $_SESSION["escuelaCab"] ?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Curso</th>
                                  <td class="h5"><b><?= $_SESSION["asignaturaCab"] ?></b></td>
                                </tr>
                                <tr>
                                  <th scope="row">Código del Curso</th>
                                  <td><?= $_SESSION["codasignaturaCAb"] ?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Grupo del Curso</th>
                                  <td><?= $_SESSION["grupo"] ?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Aula</th>
                                  <td><?= $_SESSION["aula"] ?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Semana</th>
                                  <td><?php $semanas=date("W"); $_SESSION["semana"]= $semanas -16;  echo $_SESSION["semana"]; ?></td>
                                </tr>
                                <tr>
                                  <th scope="row">Tema de avance</th>
                                  <td>
                                    <input name="tema" class="form-control" type="text" placeholder="Ingrese el tema..." data-toggle='modal' data-target='#modalTemaSilabico' required>
                                  </td>
                                </tr>
                                <tr>
                                  <th scope="row">% de Avance a la fecha</th>
                                  <td>
                                    <div class="progress progress-md mb-2">
                                      <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $rpta?>" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                      <?php
                                        echo $rpta.' %';
                                        $_SESSION["porcentaje"]  = $rpta;
                                      ?>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              	<?php if(!isset($_POST["enviar"]) ): ?>
                                  <tr>
                                  <th scope="row"></th>
                                    <td>
                                      <input type="hidden" name="enviar" class="form-control" value="guardadoCabe">
                                      <button type="submit" name="guardarCab" class="btn btn-success" id="saveCab"><i class="fas fa-fw fa-check"></i> Guardar Avance Silábico</button>
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
  <?php } ?>
