<?php

    require_once("config/conexion.php");

    class Asistencia extends Conectar {

      //metodo para mostrar temas del docente en un determinado curso
      public function get_tema_curso_docente($correo){
          $conectar=parent::conexion();
          parent::set_names();

          $email = $correo;
          $tema = "no hay TEMAS";
          if (!empty($correo)) {
              $sql = "SELECT tema_silabico.semana,  tema_silabico.tema as tema, tema_silabico.acumulado as porcentaje FROM tema_silabico
                      WHERE tema_silabico.correo = ? and tema_silabico.escuela = ? and tema_silabico.codasig = ? and tema_silabico.asignatura = ? and tema_silabico.grupo = ? and tema_silabico.semana = ?";
              $sql = $conectar->prepare($sql);

              $sql->bindValue(1, $email);                         //var_dump($email);
              $sql->bindValue(2, $_SESSION["escuelaCab"]);        //var_dump($_SESSION["escuelaCab"]);
              $sql->bindValue(3, $_SESSION["codasignaturaCAb"]);  //var_dump($_SESSION["codasignaturaCAb"]);
              $sql->bindValue(4, $_SESSION["asignaturaCab"]);     //var_dump($_SESSION["asignaturaCab"]);
              $sql->bindValue(5, $_SESSION["grupo"]);             //var_dump($_SESSION["grupo"]);
              $sql->bindValue(6, $_SESSION["semana"]);            //var_dump($_SESSION["semana"]);
              $sql->execute();

              $row_cnt = $sql->rowCount();                          //var_dump($row_cnt); echo "<br/>";
              $resultado_temas = $sql->fetchAll(PDO::FETCH_ASSOC);  //var_dump($resultado_temas);
          }else {
            echo "el correo esta vacio";
          }

          if ($resultado_temas) { //var_dump($resultado_temas);
                $tema = $resultado_temas;
                //var_dump($tema);
          }else {
                $tema = "algun dato no es correcto para obtener el tema de la semana";
          }
          $_SESSION["porcentaje"]= $tema[0]["porcentaje"];
          $_SESSION["row_cnt_temas_cap"] = $row_cnt;
          return $tema;
      }

     //metodo para registrar asistencia
      public function registrar_asistencia_cabecera($row,$nombre,$facultad,$escuela,$asignatura,$codasig,$grupo,$horaini,$horafin,$dia,$correo,$aula,$semana,$tema,$porcentaje){

            $conectar=parent::conexion();
            parent::set_names();

            if(isset($_POST["enviar"])){
                $_SESSION['estadoRegistroCab']= false;
                //$row = self::get_asistencia_cabecera();
                //var_dump($row);
                if($row ==0){
                    $sql="insert into asistencia_cabecera
                          values(null,?,?,?,?,?,?,?,?,?,CURDATE(),?,?,?,?,?,?)";

                    $sql=$conectar->prepare($sql);

                    $sql->bindValue(1, $_SESSION["nombreCab"]);
                    $sql->bindValue(2, $_SESSION["facultadCab"]);
                    $sql->bindValue(3, $_SESSION["escuelaCab"]);
                    $sql->bindValue(4, $_SESSION["asignaturaCab"]);
                    $sql->bindValue(5, $_SESSION["codasignaturaCAb"]);
                    $sql->bindValue(6, $_SESSION["grupo"]);
                    $sql->bindValue(7, $_SESSION["hora_inicial"]);
                    $sql->bindValue(8, $_SESSION["hora_final"]);
                    $sql->bindValue(9, $_SESSION["dia"]);
                    $sql->bindValue(10, $_SESSION["correo"]);
                    $sql->bindValue(11, $_SESSION["aula"]);
                    $sql->bindValue(12, $_SESSION["semana"]);
                    $sql->bindValue(13, $_POST["tema"]);
                    $sql->bindValue(14, $_POST["porcentaje"]);
                    $sql->bindValue(15, "1");


                    $con = $sql->execute();
                    if($con){
                        $_SESSION['estadoRegistroCab'] = true;
                    }
                }else{
                    header("Location:".Conectar::ruta()."asistencias.php");
                    $_SESSION["recuperandoinfo"]= "si";
                }
                $conectar=null;
		            unset($_POST["enviar"]);
            	  return $_SESSION['estadoRegistroCab'];
	        }
        }

        public function get_asistencia_cabecera(){
            $conectar=parent::conexion();
                parent::set_names();

                $sql="SELECT * FROM `asistencia_cabecera`
                WHERE `facultad` = ?  AND `programa` = ?
                AND  `asignatura` =? AND `codasig`=? AND `grupo` =?
                AND `horaini`=? AND `horafin`=?
                and `dia`=? AND `fecha`=CURDATE() AND `correo`= ?";

                $sql=$conectar->prepare($sql);
                $sql->bindValue(1, $_SESSION["facultadCab"]);
                $sql->bindValue(2, $_SESSION["escuelaCab"]);
                $sql->bindValue(3, $_SESSION["asignaturaCab"]);
                $sql->bindValue(4, $_SESSION["codasignaturaCAb"]);
                $sql->bindValue(5, $_SESSION["grupo"]);
                $sql->bindValue(6, $_SESSION["hora_inicial"]);
                $sql->bindValue(7, $_SESSION["hora_final"]);
                $sql->bindValue(8, $_SESSION["dia"]);
                $sql->bindValue(9, $_SESSION["correo"]);
                $sql->execute();

                $row_cnt = $sql->rowCount();
                $resultado = $sql->fetchAll();
                return $row_cnt;
        }

        public function get_datos_asistencia_cabecera(){
            $conectar=parent::conexion();
                parent::set_names();

                $sql="SELECT * FROM `asistencia_cabecera`
                WHERE `facultad` = ?  AND `programa` = ?
                AND  `asignatura` =? AND `codasig`=? AND `grupo` =?
                AND `horaini`=? AND `horafin`=?
                and `dia`=? AND `fecha`=CURDATE() AND `correo`= ?";

                $sql=$conectar->prepare($sql);
                $sql->bindValue(1, $_SESSION["facultadCab"]);       //var_dump($_SESSION["facultadCab"]);
                $sql->bindValue(2, $_SESSION["escuelaCab"]);        //var_dump($_SESSION["escuelaCab"]);
                $sql->bindValue(3, $_SESSION["asignaturaCab"]);     //var_dump($_SESSION["asignaturaCab"]);
                $sql->bindValue(4, $_SESSION["codasignaturaCAb"]);  //var_dump($_SESSION["codasignaturaCAb"]);
                $sql->bindValue(5, $_SESSION["grupo"]);             //var_dump($_SESSION["grupo"]);
                $sql->bindValue(6, $_SESSION["hora_inicial"]);      //var_dump($_SESSION["hora_inicial"]);
                $sql->bindValue(7, $_SESSION["hora_final"]);        //var_dump($_SESSION["hora_final"]);
                $sql->bindValue(8, $_SESSION["dia"]);               //var_dump($_SESSION["dia"]);
                $sql->bindValue(9, $_SESSION["correo"]);            //var_dump($_SESSION["correo"]);
                $sql->execute();                                    //var_dump($sql->execute());

                $resultado = $sql->fetchAll();	                    //var_dump($resultado);
                $resp = "";
                foreach( $resultado as $resul){
                    $resp= $resul;
                }                                                   //var_dump($resp);
                return $resp;
        }





        public function getFechaCabecera($correo, $escuela, $asignatura,$codasig, $grupo, $aula){
            $conectar=parent::conexion();
            parent::set_names();

            $rpta= false;

            $sql="SELECT fecha FROM asistencia_cabecera WHERE
            correo =? and programa =? and asignatura =? and codasig=? and grupo= ? and aula=?";

            $sql=$conectar->prepare($sql);

            $sql->bindValue(1, $_SESSION["correo"]);
            $sql->bindValue(2, $_SESSION["escuelaCab"]);
            $sql->bindValue(3, $_SESSION["asignaturaCab"]);
            $sql->bindValue(5, $_SESSION["codasignaturaCAb"]);
            $sql->bindValue(4, $_SESSION["grupo"]);
            $sql->bindValue(5, $_SESSION["aula"]);

            $con = $sql->execute();

            return $con->fetchAll();

        }

        public function getIdCabecera($correo,$codasig, $grupo){

            $conectar=parent::conexion();
            parent::set_names();

            $correo = $correo; //var_dump($correo);
            $codasig = $codasig; //var_dump($codasig);
            $grupo = $grupo; //var_dump($grupo);

            $rpta = false;

            if(empty($correo)){
                header("Location:".Conectar::ruta()."index.php");
                exit();
            }else{
                $sql="SELECT id FROM `asistencia_cabecera` WHERE
                        correo =? and  codasig =? AND grupo =? AND horaini=? AND horafin=? AND fecha = CURDATE()";

                $sql=$conectar->prepare($sql);

                $sql->bindValue(1, $correo);
                $sql->bindValue(2, $codasig);
                $sql->bindValue(3, $grupo);
                $sql->bindValue(4, $_SESSION["hora_inicial"]);
                $sql->bindValue(5, $_SESSION["hora_final"]);
                $sql->execute();

                $resultado = $sql->fetch();

                return $resultado['id'];
            }


        }

        public function getCountEstadoAsistencia($esta, $idcabecera){

            $conectar=parent::conexion();
            parent::set_names();

            $correo = $_SESSION["correo"];

            if(empty($_SESSION["id_cabecera"][0])){
                if(empty($_SESSION["idcabeceracontinuo"][0])){
                    $fk = '';
                }else{
                    $fk =$_SESSION["idcabeceracontinuo"][0];
                }
            }else{
                $fk = $_SESSION["id_cabecera"][0];
            }

            //$fk =(empty($_SESSION["id_cabecera"][0]))? $_SESSION["idcabeceracontinuo"][0] : $_SESSION["id_cabecera"][0];
            $fk =(empty($fk))? $idcabecera : $fk;
            $estado = $esta;

            if(empty($correo)){
                header("Location:".Conectar::ruta()."index.php");
                exit();
            }else{
                $sql="SELECT COUNT(estado) FROM `asistencia_detalle` WHERE fk_asistencia_cabecera = ? and estado = ?";

                $sql=$conectar->prepare($sql);

                $sql->bindValue(1, $fk);
                $sql->bindValue(2, $estado);
                $sql->execute();

                $resultado = $sql->fetch();

                foreach( $resultado as $resul){
                    $resp= $resul;
                }

                return $resp;
            }


        }

        public function getHoraFinCurso(){

            $conectar=parent::conexion();
            parent::set_names();

            $correo = $_SESSION["correo"];

            if(empty($correo)){
                header("Location:".Conectar::ruta()."index.php");
                exit();
            }else{
                $sql="SELECT  DISTINCT hora_fin

                from dutic_matriculados_19, dutic_docentes_19, dutic_horarios_19

                WHERE dutic_matriculados_19.codasig=dutic_docentes_19.codasig
                and dutic_docentes_19.codasig=dutic_horarios_19.codasig
                and dutic_matriculados_19.escuela = dutic_docentes_19.escuela
                and dutic_docentes_19.escuela=dutic_horarios_19.escuela
                and dutic_matriculados_19.grupo = dutic_docentes_19.grupo
                and dutic_docentes_19.grupo=dutic_horarios_19.grupo

                and dutic_horarios_19.dia = WEEKDAY(CURDATE())+1
                and  CAST('09:45' AS time)
                BETWEEN CAST(dutic_horarios_19.hora_ini AS time) AND DATE_SUB(CAST(dutic_horarios_19.hora_fin AS time), INTERVAL 1 MINUTE)

                and dutic_docentes_19.correo = ?";

                $sql=$conectar->prepare($sql);

                $sql->bindValue(1, $correo);
                $sql->execute();

                $resultado = $sql->fetch();

                foreach( $resultado as $resul){
                    $resp= $resul;
                }

                return $resp;
            }
        }
    }
?>
