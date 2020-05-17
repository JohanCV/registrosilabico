<?php
  if ($_GET["value"]) {
      $email = $_GET["value"];
      $findme = "/";
      $tamanio = strlen($email);
      $pos = strpos($email, $findme);
      $partcorreo = substr($email,0,$pos);

      echo "$partcorreo";
  }
 ?>
<!DOCTYPE html>
<html lang="en">

<head>

 <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <meta name="description" content="">
 <meta name="author" content="">

 <title>Registro Silabico</title>

 <!-- Custom fonts for this template-->
 <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
 <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

 <!-- Custom styles for this template-->
 <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

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
                 <div class="text-center">
                   <p class="login-text-title mb-4">Estimado(a) docente por favor ingrese su cuenta de correo institucional y su contraseña:</p>
                 </div>
                 <div class="form-group">
                     <input type="submit" class="btn btn-primary btn-user btn-block" value="Registrar" data-toggle='modal' data-target='#modalLoginForm'>
                 </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>

 <!-- Modal -->
        <div class="modal fade modalExportarAsistencia" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
             <div class="modal-dialog" role="document">
                 <div class="modal-content">
                 <div class="modal-header text-center">
                     <h4 class="modal-title w-100 font-weight-bold">Seleccione fecha de inicio y fecha de fin del reporte</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <form method="POST">
                 <div class="modal-body mx-3">
                     <div class="md-form mb-5 exportar-form">
                         <i class="fas fa-calendar col-md-2 fa-2x prefix grey-text"></i>
                         <input type="text" class="form-control col-md-9" name="daterange" value="08/08/2019 - 08/08/2019" />
                     </div>

                 </div>
                 <div class="modal-footer d-flex justify-content-center">
                 <form action="" method="post">
                 <button type="submit" id="btnExport" name='export'
                     value="Export to Excel" class="btn btn-success"><a style="color: #fff;">Exportar a Excel</a></button>
                 </form>
                 <!--
                 <button class="btn btn-danger"><a style="color: #fff;">Cancelar</a></button>
                 <button class="btn btn-success"><a style="color: #fff;">Exportar</a></button>
                 -->
                 </div>
                 </form>
                 </div>
             </div>
          </div>

 <!-- Bootstrap core JavaScript-->
 <script src="vendor/jquery/jquery.min.js"></script>
 <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

 <!-- Core plugin JavaScript-->
 <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

 <!-- Custom scripts for all pages-->
 <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
