<?php
  require_once("config/conexion.php");
  //if (isset($_GET["value"])) {
      //$emailmd5 = $_GET["value"];
      (isset($_GET["op"])? $op = $_GET["op"]: "no hay opcion");
      //var_dump($_GET["op"]); var_dump($op);
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
                                         <form method="post">
                                             <div class="modal-body mx-3">
                                                 <!-- Texto NO hay clase programada -->
                                                 <div class="text-center message-text">
                                                   <i class="fas fa-sad-cry fa-6x"></i>
                                                   <?php
                                                        $op= $op;
                                                        switch ($op) {
                                                          case '2':
                                                            echo '<p class="lead text-gray-800 mb-5">Lo sentimos, tiene un conflicto de horario de clases programadas. Por favor comuniquese con DUFA para corregir sus horarios.</p>
                                                                  <p class="text-gray-500 mb-0"></p>';
                                                            break;
                                                          case '3':
                                                            echo '<p class="lead text-gray-800 mb-5">Lo sentimos, No se encuentra en nuestra base de datos, comuniquese con el Administrador de DUFA.</p>
                                                                    <p class="text-gray-500 mb-0"></p>';
                                                            break;
                                                          case '4':
                                                              echo '<p class="lead text-gray-800 mb-5">Lo sentimos, No se encuentra información de su curso, comunicarse con el Administrador de SISCAD.</p>
                                                                      <p class="text-gray-500 mb-0"></p>';
                                                              break;

                                                          default:
                                                            echo '<p class="lead text-gray-800 mb-5">Lo sentimos, ahora no tiene una clase programada.</p>
                                                                  <p class="text-gray-500 mb-0"></p>';
                                                            break;
                                                        }
                                                   ?>
                                                 </div>
                                             </div>
                                             <div class="modal-footer d-flex justify-content-center">
                                               <form action="" method="post">
                                                    <button type="submit" id="btnTerminarTema" name='btnTerminarTema'
                                                            value="Terminar Tema" class="btn btn-danger">Volver<a href= "https://aulavirtual.unsa.edu.pe/aulavirtual/"></a>
                                                    </button>
                                               </form>
                                             </div>
                                         </form>
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
// }else{
//   header("Location:".Conectar::ruta_aulavirtual());
// }
?>
