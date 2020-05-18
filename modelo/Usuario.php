<?php

require_once("config/conexion.php");
require_once("Asistencia.php");

class Usuario extends Conectar {

        public function login(){
            $conectar=parent::conexion();
            parent::set_names();

            if(isset($_POST["enviar"])){

                //INICIO DE VALIDACIONES
                $correo = $_POST["correo"];
                $password = $_POST["password"];

                $findme = "@";
                $defaul = "@unsa.edu.pe";
                $tamanio = strlen($correo);
                $pos = strpos($correo, $findme);
                $partcorreo = substr($correo,$pos,$tamanio-1);


                if(empty($correo) and empty($password)){
                    header("Location:".Conectar::ruta()."index.php");
                    exit();
                }
                else{
                    if($partcorreo === $defaul){
                        $sql= "SELECT * FROM usuarios WHERE correo = ? and dni=?";

                        $sql=$conectar->prepare($sql);

                        $sql->bindValue(1, $correo);
                        $sql->bindValue(2, $password);
                        $sql->execute();
                        $resultado = $sql->fetch();

                        //si existe el registro entonces se conecta en session
                        if(is_array($resultado) and count($resultado)>0){
                            /*IMPORTANTE: la session guarda los valores de los campos de la tabla de la bd*/
                            $_SESSION["codasignatura"] = $resultado["codasig"];
                            $_SESSION["correo"] = $resultado["correo"];
                            $_SESSION["nombre"] = $resultado["nombres"];
                            $_SESSION["escuela"] = $resultado["escuela"];
                            $_SESSION["tipo"] = $resultado["tipo"];
                            $_SESSION["estadopass"] = $resultado["estadopass"];

                            self::getCabeceraAsistencia();
                            $asis =new Asistencia();

                            $id_cabecera = $asis->getIdCabecera($_SESSION["correo"], $_SESSION["codasignaturaCAb"], $_SESSION["grupo"]);
                            $rowcabecera = $asis->get_asistencia_cabecera();
                            $rowdetalle = $asis->getFilasAsistenciaDetalle($id_cabecera);

                            //var_dump($rowcabecera); //print_r($this->pdo->errorInfo());
                            //var_dump($rowdetalle); print_r($rowdetalle);

                            if($rowcabecera == 0){//echo $rowcabecera;
                                header("Location:".Conectar::ruta()."asistencia.php");
                            }else{
                                switch ($rowdetalle) {
                                    case '0':
                                        //Usar este header si vamos a tomar asistencia de alumnos
                                        header("Location:".Conectar::ruta()."asistencias.php");
                                        break;
                                    default:
                                        header("Location:".Conectar::ruta()."asistenciaedit.php");
                                        break;
                                }


                            }

                        }else{
                            echo "Su usuario o contraseña son incorrectos. Ingrese nuevamente sus datos.";
                            $_SESSION["exitosologeo"] = "no";
                            header("Location:".Conectar::ruta()."index.php?m=2");
                        }
                    }else{
                        echo "Su correo tiene que ser Institucional (sucorreo@unsa.edu.pe).";
                        header("Location:".Conectar::ruta()."index.php?m=1");
                    }

                }
            }
        }


        public function getEmailMd5($email_md5){
            $conectar=parent::conexion();
            parent::set_names();
            $email = $email_md5;
            $correo = "";

            if(empty($email)){
                header("Location:".Conectar::ruta_aulavirtual());
                exit();
            }else {
                $sql = "SELECT * FROM `docente_md5` WHERE email_md5 = ?";
                $sql=$conectar->prepare($sql);

                $sql->bindValue(1, $email);
                $sql->execute();
                $resultado = $sql->fetch();

                if($email == $resultado["email_md5"]){
                  $correo = $resultado["correo"];
                }else {
                  $correo = "nomatch";
                }
            }
            return $correo;
        }

        public function getDatosDocente($docente){
            $conectar=parent::conexion();
            parent::set_names();

            $correo = $docente;
            $rpta = false;
            if(empty($correo)){
                header("Location:".Conectar::ruta());
                exit();
            }else{//cambiar la consulta por la de hora now() en el server
                $sql = "SELECT DISTINCT dutic_matriculados_20.facultad,  dutic_docentes_20.escuela, dutic_matriculados_20.asignatura, dutic_docentes_20.codasig, dutic_docentes_20.grupo,
                  			 dutic_horarios_20.aula, dutic_horarios_20.dia, dutic_horarios_20.hora_ini,dutic_horarios_20.hora_fin, concat(dutic_docentes_20.nombre,' ',dutic_docentes_20.paterno,' ',dutic_docentes_20.materno) as nombres

                  from dutic_matriculados_20, dutic_docentes_20, dutic_horarios_20
                  WHERE dutic_matriculados_20.codasig=dutic_docentes_20.codasig
                  and dutic_docentes_20.codasig=dutic_horarios_20.codasig
                  and dutic_matriculados_20.escuela = dutic_docentes_20.escuela
                  and dutic_docentes_20.escuela=dutic_horarios_20.escuela
                  and dutic_matriculados_20.grupo = dutic_docentes_20.grupo
                  and dutic_docentes_20.grupo=dutic_horarios_20.grupo

                  #and dutic_horarios_20.dia = WEEKDAY(CURDATE())+1
                  and dutic_horarios_20.dia = 1
                  and CAST('09:45' AS time)
                  #and  time (NOW())
                  BETWEEN CAST(dutic_horarios_20.hora_ini AS time) AND DATE_SUB(CAST(dutic_horarios_20.hora_fin AS time), INTERVAL 1 MINUTE)
                  and dutic_docentes_20.correo = ?
                  ORDER BY dutic_docentes_20.escuela, dutic_horarios_20.dia, dutic_horarios_20.hora_ini";

                $sql=$conectar->prepare($sql);

                $sql->bindValue(1, $correo);
                $sql->execute();

                $row_cnt = $sql->rowCount();
                $resultado = $sql->fetch();

                switch ($row_cnt) {
                    case '0':
                        header("Location:".Conectar::ruta()."vista/mensaje.php");
                        break;
                    case '1':
                        $_SESSION["facultadCab"] = $resultado["facultad"];
                        $_SESSION["escuelaCab"] = $resultado["escuela"];
                        $_SESSION["asignaturaCab"] = $resultado["asignatura"];
                        $_SESSION["codasignaturaCAb"] = $resultado["codasig"];
                        $_SESSION["grupo"] = $resultado["grupo"];
                        $_SESSION["aula"] = $resultado["aula"];
                        $_SESSION["dia"] = $resultado["dia"];
                        $_SESSION["hora_inicial"] = $resultado["hora_ini"];
                        $_SESSION["hora_final"] = $resultado["hora_fin"];
                        $_SESSION["nombreCab"] = $resultado["nombres"];
                        break;
                    default:
                        header("Location:".Conectar::ruta()."vista/mensaje2.php");
                        break;
                }

                if($resultado){
                    $rpta =true;
                }
                return $rpta;
                //var_dump($rpta);
                //return $resultado;

            }

        }

        public function getDetalleAsistencia(){
            $conectar=parent::conexion();
            parent::set_names();
            //echo "detalle";
            $codAsig = $_SESSION["codasignaturaCAb"];
            $correo = $_SESSION["correo"];
            $grupo = $_SESSION["grupo"];
            $dia = $_SESSION["dia"];

            if(empty($correo)){
                header("Location:".Conectar::ruta()."index.php");
                exit();
            }else{
                $sql = "SELECT distinct m.nombre nombre_s,m.paterno apellido_p,m.materno apellido_m
                FROM`dutic_matriculados_19` m,`dutic_docentes_19` d,`dutic_horarios_19` h
                where
                m.codasig=d.codasig and
                h.codasig=d.codasig and
                d.codasig=? and
                d.correo=? and
                m.escuela=d.escuela and
                m.grupo=? and
                h.dia=?";

                $sql=$conectar->prepare($sql);

                $sql->bindValue(1, $codAsig);
                $sql->bindValue(2, $correo);
                $sql->bindValue(3, $grupo);
                $sql->bindValue(4, $dia);
                $sql->execute();
                $resultado = $sql->fetch();

                //si existe el registro entonces se conecta en session
                if(is_array($resultado) and count($resultado)>0){

                    $_SESSION["nombres"] = $resultado["nombre_s"];
                    $_SESSION["apellidop"] = $resultado["apellido_p"];
                    $_SESSION["apellidom"] = $resultado["apellido_m"];
                    //header("Location:".Conectar::ruta()."asistencia.php");
                }else{
                    //si no existe el registro
                    //header("Location:".Conectar::ruta()."404.php");
                }

                return $resultado;
            }
        }

        public function getCursosDocente(){

            $conectar=parent::conexion();
            parent::set_names();

            $correo = $_SESSION["correo"];

            if(empty($correo)){
                header("Location:".Conectar::ruta()."index.php");
                exit();
            }else{
                $sql = "SELECT DISTINCT dutic_docentes_19.escuela, dutic_docentes_19.codasig, dutic_matriculados_19.asignatura, dutic_docentes_19.grupo

                from dutic_matriculados_19, dutic_docentes_19, dutic_horarios_19

                WHERE dutic_matriculados_19.codasig=dutic_docentes_19.codasig
                and dutic_docentes_19.codasig=dutic_horarios_19.codasig
                and dutic_matriculados_19.escuela = dutic_docentes_19.escuela
                and dutic_docentes_19.escuela=dutic_horarios_19.escuela
                and dutic_matriculados_19.grupo = dutic_docentes_19.grupo
                and dutic_docentes_19.grupo=dutic_horarios_19.grupo


                and dutic_docentes_19.correo=?
                ORDER BY dutic_docentes_19.escuela, dutic_matriculados_19.asignatura, dutic_docentes_19.grupo";

                $sql=$conectar->prepare($sql);

                $sql->bindValue(1, $correo);
                $sql->execute();
                $resultado = $sql->fetchAll();

                return $resultado;
            }
        }
    }

?>
