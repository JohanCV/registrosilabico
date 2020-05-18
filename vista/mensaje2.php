<?php
  require_once("../config/conexion.php");

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
                                  <!-- Begin Page Content -->
                                  <div class="container-fluid">

                                    <!-- Texto Conflicto de clases -->
                                    <div class="text-center message-text" style="margin-top: 50px;">
                                      <!--div class="error mx-auto" data-text="404">404</div-->
                                      <p class="lead text-gray-800 mb-5">Lo sentimos, tiene un conflicto de horario de clases programadas. Por favor comuniquese con DUFA para corregir sus horarios.</p>
                                      <p class="text-gray-500 mb-0"></p>
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
