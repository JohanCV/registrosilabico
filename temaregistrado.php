<?php
  require_once("config/conexion.php");
  if (isset($_POST["temasilabico"])) {
      $temasilabico = $_POST["temasilabico"];
      $aulavirtual = "";
      $localhost = "localhost";
      $emailmd5 = "33ff7d62b29b24e8bca8af8531159ea9";
      echo "$temasilabico";
      echo "<br/> PROBANDO";

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
                                                <h5>Su Tema Silabico fue registrado exitosamente.</h5>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                              <form action="" method="post">
                                                   <button type="submit" id="btnEditarTema" name='btnEditarTema'
                                                           value="Editar Tema" class="btn btn-warning" style="color: #fff;">Editar<a href=<?php Conectar::ruta($localhost,$emailmd5); ?>></a>
                                                   </button>
                                                   <button type="submit" id="btnTerminarTema" name='btnTerminarTema'
                                                           value="Terminar Tema" class="btn btn-danger">Terminar<a href=<?php Conectar::ruta_aulavirtual(); ?>></a>
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
}else{
  header("Location:".Conectar::ruta_aulavirtual());
}
 ?>
