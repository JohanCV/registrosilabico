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

                if(empty($correo) and empty($password)){
                    header("Location:".Conectar::ruta()."index.php");
                    exit();
                }
                else{
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
                        $_SESSION["estadopass"] = $resultado["escuela"];
                        
                        self::getCabeceraAsistencia();
                        $asis =new Asistencia();
                        $row = $asis->get_asistencia_cabecera();
                         var_dump($row);
                        if($row == 0){echo $row;
                            header("Location:".Conectar::ruta()."asistencia.php");
                        }else{
                             header("Location:".Conectar::ruta()."asistenciaedit.php");
                        }
                        
                    }else{
                        //echo" no existe el registro";
                        $_SESSION["exitosologeo"] = "no";
                        //header("Location:".Conectar::ruta()."index.php");
                        
                    }
                }
            }
        }        

        public function login_mac($mac){
            $conectar=parent::conexion();
            parent::set_names();

            if(isset($_POST["enviar"])){

                //INICIO DE VALIDACIONES
                $correo = $_POST["correo"];
                $password = $_POST["password"];   
                $macpc = $mac;           

                if(empty($correo) and empty($password)){
                    header("Location:".Conectar::ruta()."index.php");
                    exit();
                }
                else{
                    $sql= "SELECT DISTINCT dutic_matriculados_19.facultad,  usuarios.escuela, dutic_matriculados_19.asignatura, 
                    usuarios.codasig, usuarios.grupo, dutic_horarios_19.aula, dutic_horarios_19.dia, dutic_horarios_19.hora_ini,
                    dutic_horarios_19.hora_fin, usuarios.nombres, usuarios.correo, usuarios.dni, usuarios.tipo, usuarios.estadopass

                    from dutic_matriculados_19, usuarios, dutic_horarios_19
                    
                    WHERE dutic_matriculados_19.codasig=usuarios.codasig
                    and usuarios.codasig=dutic_horarios_19.codasig
                    and dutic_matriculados_19.escuela = usuarios.escuela
                    and usuarios.escuela=dutic_horarios_19.escuela
                    and dutic_matriculados_19.grupo = usuarios.grupo
                    and usuarios.grupo=dutic_horarios_19.grupo
                    
                    and dutic_horarios_19.dia = 3
                    and  CAST('09:40' AS time)
                    BETWEEN CAST(dutic_horarios_19.hora_ini AS time) AND DATE_SUB(CAST(dutic_horarios_19.hora_fin AS time), INTERVAL 1 MINUTE)
                    
                    and usuarios.correo= ? and usuarios.dni=?";

                    $sql=$conectar->prepare($sql);
  
                    $sql->bindValue(1, $correo);
                    $sql->bindValue(2, $password);
                    $sql->execute();
                    $resultado = $sql->fetch();

                    
                    
                    //si existe el registro entonces se conecta en session
                    if(is_array($resultado) and count($resultado)>0 ){
                        /*IMPORTANTE: la session guarda los valores de los campos de la tabla de la bd*/      
                        $_SESSION["codasignatura"] = $resultado["codasig"];                  
                        $_SESSION["correo"] = $resultado["correo"];
                        $_SESSION["nombre"] = $resultado["nombres"];
                        $_SESSION["escuela"] = $resultado["escuela"];
                        $_SESSION["aula"] = $resultado["aula"];
                        $_SESSION["tipo"] = $resultado["tipo"];
                        $_SESSION["estadopass"] = $resultado["estadopass"];
                            
                            if(!empty($_SESSION["aula"])){
                                //validando la mac
                                $escuela = $_SESSION["escuela"];
                                $aula =  $_SESSION["aula"];

                                $sqlmac = "SELECT * FROM mac WHERE escuela=? and aula=?";
                                $sqlmac=$conectar->prepare($sqlmac);

                                $sqlmac->bindValue(1, $escuela);
                                $sqlmac->bindValue(2, $aula);
                                $sqlmac->execute();
                                $resultadomac = $sqlmac->fetch();

                                if(is_array($resultadomac) and count($resultadomac)>0 and strcmp( $resultadomac["mac"], $macpc)){
                                    header("Location:".Conectar::ruta()."asistencia.php");
                                }else{
                                    $_SESSION["exitosologeo"] = "no";
                                }      
                            }
                    }else{
                        //si no existe el registro
                        $_SESSION["exitosologeo"] = "no";
                        //header("Location:".Conectar::ruta()."index.php");
                        
                    }
                }
            }
        }        
        
        public function actualizarContrasenia($pass1, $pass2){
            $conectar=parent::conexion();
            parent::set_names();

            $correo = $_SESSION["correo"];
            $pas1 = $_POST["pass1"];
            $pas2 = $_POST["pass2"];
            
            if($pas1 == $pas2){                       

                    $sql= "UPDATE usuarios SET dni=?  WHERE correo =? ";

                    $sql=$conectar->prepare($sql);
  
                    $sql->bindValue(1, $pas1);
                    $sql->bindValue(2, $correo);
                    $sql->execute();
                    $resultado = $sql->fetch();
                //echo"se guardo exitosamente";
                $_SESSION["exitosochangepass"] = "si";
            }else{
                //echo"no coinciden";
                $_SESSION["exitosochangepass"] = "no";
            }
        }
        
        public function getMAC(){
            $conectar=parent::conexion();
            parent::set_names();

            $localIP = getHostByName(getHostName());

            ob_start(); // Turn on output buffering
            system('ipconfig /all'); //Execute external program to display output
            $mycom=ob_get_contents(); // Capture the output into a variable
            $fileEndEnd = utf8_encode ( $mycom );
            ob_clean();

            $find_word = "física";
            $pmac = strpos($mycom, $find_word); 
            $mac=substr($mycom,($pmac+1753),19); // Get Physical Address
            return $mac;
        }


        public function getCabeceraAsistencia(){
            $conectar=parent::conexion();
            parent::set_names();
            //echo "cabecera";
            $correo = $_SESSION["correo"];
            $rpta = false;
            if(empty($correo)){
                header("Location:".Conectar::ruta()."index.php");
                exit();
            }else{//cambiar la consulta por la de hora now() en el server
                $sql = "SELECT DISTINCT dutic_matriculados_19.facultad,  dutic_docentes_19.escuela, dutic_matriculados_19.asignatura, dutic_docentes_19.codasig, dutic_docentes_19.grupo,
			 dutic_horarios_19.aula, dutic_horarios_19.dia, dutic_horarios_19.hora_ini,dutic_horarios_19.hora_fin, dutic_docentes_19.nombres

                from dutic_matriculados_19, dutic_docentes_19, dutic_horarios_19
                
                WHERE dutic_matriculados_19.codasig=dutic_docentes_19.codasig
                and dutic_docentes_19.codasig=dutic_horarios_19.codasig
                and dutic_matriculados_19.escuela = dutic_docentes_19.escuela
                and dutic_docentes_19.escuela=dutic_horarios_19.escuela
                and dutic_matriculados_19.grupo = dutic_docentes_19.grupo
                and dutic_docentes_19.grupo=dutic_horarios_19.grupo
                
                and dutic_horarios_19.dia = WEEKDAY(CURDATE())+1
               
                and CAST('09:45' AS time)
                BETWEEN CAST(dutic_horarios_19.hora_ini AS time) AND DATE_SUB(CAST(dutic_horarios_19.hora_fin AS time), INTERVAL 1 MINUTE)
                
                and dutic_docentes_19.correo = ?
                ORDER BY dutic_docentes_19.escuela, dutic_horarios_19.dia, dutic_horarios_19.hora_ini";

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
                        header("Location:".Conectar::ruta()."mensaje2.php");
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

        public function getReport(){

            $conectar=parent::conexion();
            parent::set_names();

            $correo = $_SESSION["correo"];
            $escuela = $_COOKIE["a"];
            $codAsig = $_COOKIE["b"];
            $grupo = $_COOKIE["c"];
            $curso = $_COOKIE["d"];

            
            if(empty($correo) && empty($escuela) && empty($codAsig)&& empty($grupo)){
                header("Location:".Conectar::ruta()."404.php");
                exit();
            }else{
                $sql = "SELECT asistencia_cabecera.fecha, CONCAT(asistencia_detalle.apellidop,' ', asistencia_detalle.apellidom,', ', asistencia_detalle.nombres)as Estudiantes, asistencia_detalle.estado 
                FROM asistencia_cabecera, asistencia_detalle 
                WHERE asistencia_detalle.fk_asistencia_cabecera = asistencia_cabecera.id
                #and CAST(asistencia_cabecera.fecha AS time) BETWEEN date (DATE_SUB(NOW(), INTERVAL 15 DAY)) and date (DATE_ADD(NOW(), INTERVAL 5 DAY))
                and CAST(asistencia_cabecera.fecha AS date) BETWEEN '".$_COOKIE["f1"]."' AND '".$_COOKIE["f2"]."'
                and asistencia_cabecera.correo=?
                and asistencia_cabecera.programa=?
                and asistencia_cabecera.codasig=?
                and asistencia_cabecera.grupo=?
                ORDER BY asistencia_cabecera.fecha";

                $sql=$conectar->prepare($sql);
                
                //$sql->bindValue(1, $fromDate);
                //$sql->bindValue(2, $endDate);
                $sql->bindValue(1, $correo);
                $sql->bindValue(2, $escuela);
                $sql->bindValue(3, $codAsig);
                $sql->bindValue(4, $grupo);
                $sql->execute();
                $resultado = $sql->fetchAll();
                
                //if(isset($_POST["export_data"])) { //click en el botón exportar
                    $filename = $curso."_".date('Ymd') . ".xls";
                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=\"$filename\"");
                    $show_coloumn = false;
                    if(!empty($resultado)) {
                        foreach($resultado as $record) {
                            if(!$show_coloumn) {
                            // display field/column names in first row
                            echo implode("\t", array_keys($record)) . "\n";
                            $show_coloumn = true;
                            }
                            echo implode("\t", array_values($record)) . "\n";
                        }
                    }
                    exit;
                //}
                
                //return $resultado;
            }

        }

    }



?>
