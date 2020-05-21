<?php
  require_once("config/conexion.php");
  if (isset(($_POST["btnEditarTema"]))) {
      require_once('ajax/editartemaregistrado.php');
  }
  if (isset($_SESSION["nombreCab"]) and isset($_SESSION["facultadCab"]) and isset($_SESSION["escuelaCab"]) AND
      isset($_SESSION["asignaturaCab"]) and isset($_SESSION["codasignaturaCAb"]) and isset($_SESSION["grupo"])AND
      isset($_SESSION["aula"]) and isset($_SESSION["semana"]) and  isset($_POST["check_list_tema"])  ) {

      // $temasilabico = $_POST["check_list_tema"];
      // $temasilabico_acumulado ="";
      // foreach ($temasilabico as $showtemasilabico) {
      //     $temasilabico_acumulado .= $showtemasilabico.",";
      // }
      //guardamos la informacion que viene del submit guardarCabTema
      if (isset($_POST["guardarCabTema"])) {  //echo "entre a guardarCabTema <br/>";
          //$_SESSION["temas_silabus"] = $temasilabico_acumulado;
          require_once('ajax/guardartemacab.php');
      }
      if (isset($_POST["btnTerminarTema"])) {  //echo "entre a guardarCabTema <br/>";
          //$_SESSION["temas_silabus"] = $temasilabico_acumulado;
          require_once('ajax/logout.php');
      }


      //var_dump($temasilabico_acumulado);
      //echo "<br/> PROBANDO";

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
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <?php if(isset($_SESSION['estadoRegistroCab']) && $_SESSION['estadoRegistroCab'] == true): ?>
                                        <form method="post">
                                            <div class="modal-body mx-3">
                                                <h5>Su Tema Silabico fue registrado exitosamente.</h5>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                              <form action="" method="post">
                                                   <button type="submit" id="btnEditarTema" name='btnEditarTema'
                                                           value="Editar Tema" class="btn btn-warning" style="color: #fff;">Editar
                                                   </button>
                                                   <button type="submit" id="btnTerminarTema" name='btnTerminarTema'
                                                           value="Terminar Tema" class="btn btn-danger">Terminar
                                                   </button>
                                              </form>
                                            </div>
                                        </form>
                                        <?php else: ?>
                                          <form method="post">
                                            <div class="modal-body mx-3">
                                                <h5>Su Tema Silabico Ya fue registrado exitosamente.</h5>
                                                <button type="submit" id="btnEditarTema" name='btnEditarTema'
                                                        value="Editar Tema" class="btn btn-warning" style="color: #fff;">Editar
                                                </button>
                                                <button type="submit" id="btnTerminarTema" name='btnTerminarTema'
                                                        value="Terminar Tema" class="btn btn-danger">Terminar
                                                </button>
                                            </div>
                                          </form>
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
  header("Location:".Conectar::ruta_aulavirtual());
  exit();
}
 ?>
