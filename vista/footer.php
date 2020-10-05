<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<script>
  $(document).on('change','input[type="checkbox"]' ,function(e) {
      if(this.id=="activo_anual") {
          if(this.checked) {
              var boolchk = $(this).val();
              var semanarecorrida_anual = 16;
              var correo = "anual.php?value=" +'<?=$_GET["value"]?>';
              if (boolchk == "activo_anual") {
                $.ajax({
                    type: 'post',
                    url: 'anual.php',
                    data: {'week' : semanarecorrida_anual},
                    //cache: false,
                    success: function(msg){
                      console.log(msg);
                    }
                })
                .done(function( msg ) {
                  //alert( "Data Saved: " + msg );
                  window.location.replace(correo);
                });
              }
          }
      }

      if(this.id=="activo_anual_marcado") {
          if(!this.checked) {
              var boolchk = $(this).val();
              var semanarecorrida_anual = 16;
              var url = "index.php?value="+'<?=$_GET["value"]?>';
              if (boolchk == "activo_anual_marcado") {
                $.ajax({
                    type: 'post',
                    url: 'anual.php',
                    data: {'week' : semanarecorrida_anual},
                    //cache: false,
                    success: function(msg){
                      console.log(msg);
                    }
                })
                .done(function( msg ) {
                  //alert( "Data Saved: " + msg );

                  window.location.replace(url);
                });
              }
          }
      }

  });
</script>
</body>

</html>
