<?php

require_once("config/conexion.php");
require_once("Asistencia.php");

class Usuario extends Conectar {

        public function getEmailMd5($email_md5){
            $conectar=parent::conexion();
            parent::set_names();
            $email = $email_md5;
            $correo = "";

            if(empty($email)){
                //header("Location:".Conectar::ruta_aulavirtual());
                echo "el email que llega a la funcion getEmailMd5 esta vacio";
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
                //header("Location:".Conectar::ruta());
                echo "el correo que llega a la funcion getDatosDocente esta vacio";
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
                        and dutic_horarios_20.dia = WEEKDAY(CURDATE())+1
                        #and dutic_horarios_20.dia = 4
                        #and CAST('20:15' AS time)
                        and  time (NOW())
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
                        header("Location:".Conectar::ruta()."mensaje.php");
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
                        header("Location:".Conectar::ruta()."mensaje.php?op=2");
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
    }

?>
