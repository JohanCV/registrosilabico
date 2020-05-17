<?php

require_once("config/conexion.php");
require_once("Asistencia.php");
require_once("Classes/PHPExcel.php");


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

        public function login_mac($macuser){
            $conectar=parent::conexion();
            parent::set_names();

            if(isset($_POST["enviar"])){

                //INICIO DE VALIDACIONES
                $correo = $_POST["correo"];
                $password = $_POST["password"];

                //var_dump($macs);

                $findme = "@";
                $defaul = "@unsa.edu.pe";
                $tamanio = strlen($correo);
                $pos = strpos($correo, $findme);
                $partcorreo = substr($correo,$pos,$tamanio-1);
                $macs = '';

                if(empty($correo) and empty($password)){
                    header("Location:".Conectar::ruta()."index.php");
                    exit();
                }
                else{
                    if($partcorreo === $defaul){
                        $sql= "SELECT usuarios.estadopass, usuarios.tipo, usuarios.escuela, usuarios.codasig, usuarios.asignatura, usuarios.grupo, usuarios.nombres, usuarios.correo, usuarios.dni, mac.mac
				                FROM usuarios INNER JOIN mac ON usuarios.escuela = mac.escuela WHERE correo  = ? and dni=? and mac=?";

                        $sql=$conectar->prepare($sql);

                        $sql->bindValue(1, $correo);
                        $sql->bindValue(2, $password);
			            $sql->bindValue(3, $macs);
                        $sql->execute();
                        $resultado = $sql->fetch();
			            //var_dump($resultado);
    			        //print_r($this->pdo->errorInfo());
                        //si existe el registro entonces se conecta en session
                        if(is_array($resultado) and count($resultado)>0){
                            /*IMPORTANTE: la session guarda los valores de los campos de la tabla de la bd*/
                            $_SESSION["codasignatura"] = $resultado["codasig"];
                            $_SESSION["correo"] = $resultado["correo"];
                            $_SESSION["nombre"] = $resultado["nombres"];
                            $_SESSION["escuela"] = $resultado["escuela"];
                            $_SESSION["tipo"] = $resultado["tipo"];
                            $_SESSION["estadopass"] = $resultado["estadopass"];
                            $_SESSION["mac"] = $resultado["mac"];
                            //var_dump($resultado["mac"]);
                            //var_dump($this->pdo->errorInfo());

                            self::getCabeceraAsistencia();
                            $asis =new Asistencia();
                            $row = $asis->get_asistencia_cabecera();

                            if(strcmp($macs,  $_SESSION["mac"])== 0){
                                if($row == 0){
                                    header("Location:".Conectar::ruta()."asistencia.php");
                                }else{
                                     header("Location:".Conectar::ruta()."asistenciaedit.php");
                                }
                            }else{
                                echo "No se puede conectar desde la PC actual, comuniquese con el admdinistrador.";
                                header("Location:".Conectar::ruta()."index.php?m=3");
                            }

                        }else{
                            echo "Descargue la llave e Inicie Session.";
                            header("Location:".Conectar::ruta()."index.php?mac=".$macs."&m=2");
                        }
                    }else{
                        echo "Su correo tiene que ser Institucional (sucorreo@unsa.edu.pe).";
                        header("Location:".Conectar::ruta()."index.php?m=1");
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

                    $sql= "UPDATE usuarios SET dni=? , estadopass= ?  WHERE correo =? ";

                    $sql=$conectar->prepare($sql);

                    $sql->bindValue(1, $pas1);
                    $sql->bindValue(2, "1");
		                $sql->bindValue(3, $correo);
                    $sql->execute();
                    $resultado = $sql->fetch();
                //echo"se guardo exitosamente";
                $_SESSION["exitosochangepass"] = "si";
            }else{
                //echo"no coinciden";
                $_SESSION["exitosochangepass"] = "no";
            }
        }

        public function getEstadoPass($correo){
                $conectar=parent::conexion();
                parent::set_names();

            $correo = $_SESSION["correo"];
            var_dump($correo);
                var_dump($this->pdo->errorInfo());
                //if(!empty($correo)){

                        $sql= "SELECT DISTINCT estadopass FROM usuarios WHERE correo =? ";

                        $sql=$conectar->prepare($sql);

                        $sql->bindValue(1, $correo);
                        $sql->execute();
                        $resultado = $sql->fetchAll();
                return $resultado["estadopass"];
                //}

        }


        public function getMAC(){
            $conectar=parent::conexion();
            parent::set_names();

            $localIP = getHostByName(getHostName());

            //ob_start(); // Turn on output buffering
	        //system('ifconfig /all'); //Execute external program to display output
            //$mycom=ob_get_contents(); // Capture the output into a variable
            //$fileEndEnd = utf8_encode ( $mycom );
            //ob_clean();

            //$find_word = "física";
            //$pmac = strpos($mycom, $find_word);
            //$mac=substr($mycom,($pmac+1753),17); // Get Physical Address
            //$ipconfig =   shell_exec ("ifconfig/all");
            //$mac = substr(shell_exec ("ifconfig/all"),1821,18);

            // return $mac;
        }


        public function getCabeceraAsistencia($usuario){
            $conectar=parent::conexion();
            parent::set_names();
            //echo "cabecera";
            $correo = $usuario."@unsa.edu.pe";
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
                #and CAST('09:45' AS time)
                and  time (NOW())

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



                $sqlfechas = " SELECT group_concat(DISTINCT asistencia_cabecera.fecha) as fechas
                                FROM asistencia_cabecera, asistencia_detalle
                                WHERE asistencia_detalle.fk_asistencia_cabecera = asistencia_cabecera.id
                                #and CAST(asistencia_cabecera.fecha AS date) BETWEEN date (DATE_SUB(NOW(), INTERVAL 15 DAY)) and date (DATE_ADD(NOW(), INTERVAL 5 DAY))
                                and CAST(asistencia_cabecera.fecha AS date) BETWEEN '".$_COOKIE["f1"]."' AND '".$_COOKIE["f2"]."'
                                and asistencia_cabecera.correo=?
                                and asistencia_cabecera.programa=?
                                and asistencia_cabecera.codasig=?
                                and asistencia_cabecera.grupo=?
                                ORDER BY asistencia_cabecera.fecha";

                $sqlfechas=$conectar->prepare($sqlfechas);
                $sqlfechas->bindValue(1, $correo);
                $sqlfechas->bindValue(2, $escuela);
                $sqlfechas->bindValue(3, $codAsig);
                $sqlfechas->bindValue(4, $grupo);
                $sqlfechas->execute();
                $resultFechas = $sqlfechas->fetchAll();

                foreach ($resultFechas as $resultFecha) {

                    $resultFechas2 = explode(",", $resultFecha['fechas']);
                  }

                /*echo $correo."\n".$escuela."\n".$codAsig."\n".$grupo."\n".$_COOKIE["f1"]."\n".$_COOKIE["f2"]."\n".$curso."\n";

                foreach ($resultFechas2 as $resultFecha2) {

                    echo $resultFecha2."\n";

                }*/

                $sql = " SELECT asistencia_detalle.apellidop as 'Apellido Paterno',asistencia_detalle.apellidom as 'Apellido Materno',asistencia_detalle.nombres as Nombres";

                foreach ($resultFechas2 as $resultFecha2) {

                    $sql.= "\n, if(asistencia_cabecera.fecha='".$resultFecha2."' , asistencia_detalle.estado, '?') as '".$resultFecha2."'";

                }

                $sql.= "\nfrom asistencia_cabecera, asistencia_detalle
                WHERE asistencia_detalle.fk_asistencia_cabecera = asistencia_cabecera.id
                #and CAST(asistencia_cabecera.fecha AS date) BETWEEN date (DATE_SUB(NOW(), INTERVAL 15 DAY)) and date (DATE_ADD(NOW(), INTERVAL 5 DAY))
                #and CAST(asistencia_cabecera.fecha AS date) BETWEEN CAST('".$resultFechas2[0]."' AS date) AND CAST('".$resultFechas2[count($resultFechas2)-1]."' AS date)
                and asistencia_cabecera.correo=?
                and asistencia_cabecera.programa=?
                and asistencia_cabecera.codasig=?
                and asistencia_cabecera.grupo=?

                order by apellidop,apellidom,nombres";

                //echo $sql."\n";

                $sql=$conectar->prepare($sql);
                $sql->bindValue(1, $correo);
                $sql->bindValue(2, $escuela);
                $sql->bindValue(3, $codAsig);
                $sql->bindValue(4, $grupo);
                $sql->execute();

                //$resultado = $sql->fetchAll();

                //$resultado = $sql;

                $filename = $curso."_".date('Ymd') . ".xlsx";
                $fila = 7; //Establecemos en que fila inciara a imprimir los datos

                $gdImage = imagecreatefrompng('img/logo.png');//Logotipo

                //Objeto de PHPExcel
                $objPHPExcel  = new PHPExcel();

                //Propiedades de Documento
                $objPHPExcel->getProperties()->setCreator("JCV")->setDescription("Reporte de Asistencias");

                //Establecemos la pestaña activa y nombre a la pestaña
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setTitle("Asistencias");

                $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
                $objDrawing->setName('Logotipo');
                $objDrawing->setDescription('Logotipo');
                $objDrawing->setImageResource($gdImage);
                $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
                $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                $objDrawing->setHeight(100);
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

                $estiloTituloReporte = array(
                'font' => array(
                'name'      => 'Arial',
                'bold'      => true,
                'italic'    => false,
                'strike'    => false,
                'size' =>13
                ),
                'fill' => array(
                'type'  => PHPExcel_Style_Fill::FILL_SOLID
                ),
                'borders' => array(
                'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_NONE
                )
                ),
                'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
                );

                $estiloTituloColumnas = array(
                'font' => array(
                'name'  => 'Arial',
                'bold'  => true,
                'size' =>10,
                'color' => array(
                'rgb' => 'FFFFFF'
                )
                ),
                'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '538ED5')
                ),
                'borders' => array(
                'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
                )
                ),
                'alignment' =>  array(
                'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
                );

                $estiloInformacion = new PHPExcel_Style();
                $estiloInformacion->applyFromArray( array(
                'font' => array(
                'name'  => 'Arial',
                'color' => array(
                'rgb' => '000000'
                )
                ),
                'fill' => array(
                'type'  => PHPExcel_Style_Fill::FILL_SOLID
                ),
                'borders' => array(
                'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
                )
                ),
                'alignment' =>  array(
                'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
                ));

                $objPHPExcel->getActiveSheet()->getStyle('A1:E4')->applyFromArray($estiloTituloReporte);
                $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->applyFromArray($estiloTituloColumnas);

                $objPHPExcel->getActiveSheet()->setCellValue('B2', $escuela);
                $objPHPExcel->getActiveSheet()->setCellValue('B3', $curso);
                $objPHPExcel->getActiveSheet()->setCellValue('B4', $correo);
                $objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
                $objPHPExcel->getActiveSheet()->mergeCells('B3:D3');
                $objPHPExcel->getActiveSheet()->mergeCells('B4:D4');

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
                $objPHPExcel->getActiveSheet()->setCellValue('A6', 'N°');
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
                $objPHPExcel->getActiveSheet()->setCellValue('B6', 'Apellido Paterno');
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
                $objPHPExcel->getActiveSheet()->setCellValue('C6', 'Apellido Materno');
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
                $objPHPExcel->getActiveSheet()->setCellValue('D6', 'Nombres');
                //$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
                //$objPHPExcel->getActiveSheet()->setCellValue('E6', 'FECHA');

                for ($k=0; $k<count($resultFechas2); $k++) {
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j, $fila, $rows[$resultFechas2[$k]]);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k+4, 6, $resultFechas2[$k]);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($k+4, 6)->applyFromArray($estiloTituloColumnas);
                    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($k+4)->setWidth(13);

                }
                if (empty($resultFechas2[0])) {
                    $objPHPExcel->getActiveSheet()->setCellValue('E6', "No hay informacion en la fecha seleccionada. Escoja una fecha correcta.");
                    //exit;
                }
                //$i=1;
                //$rows = $sql->fetch(PDO::FETCH_ASSOC);
                // $aaa= var_dump($rows);
                // while($fila < 15){

                //             $objPHPExcel->getActiveSheet()->setCellValue('A'.$fila,$i);
                //             $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, );
                //             $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, 'c');
                //             $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, 'd');
                //             $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, 'e');
                //             $i++;
                //             $fila++; //Sumamos 1 para pasar a la siguiente fila

                // }
                //Recorremos los resultados de la consulta y los imprimimos
                $i =1;
                if(empty($rows = $sql->fetch(PDO::FETCH_ASSOC))){
                    //$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila,var_dump($rows). " No hay informacion en la fecha seleccionada. Escoja una fecha correcta.");
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, "Ud. aun no ha registrado asistencia alguna. \nPor favor registre la asistencia de sus alumnos.");
                }else{
                    do {

                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $i);
                        $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $rows['Apellido Paterno']);
                        $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $rows['Apellido Materno']);
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $rows['Nombres']);
                        for ($k=0; $k<count($resultFechas2); $k++) {
                            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j, $fila, $rows[$resultFechas2[$k]]);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k+4, $fila, $rows[$resultFechas2[$k]]);
                        }
                        //$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $rows['estado']);
                        $i++;
                        $fila++; //Sumamos 1 para pasar a la siguiente fila
                    } while($rows = $sql->fetch(PDO::FETCH_ASSOC));
                    $fila = $fila-1;
                    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A7:E".$fila);
                }

                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header('Content-Type: application/vnd.ms-excel');
                header("Content-Disposition: attachment; filename=\"$filename\"");
                header('Cache-Control: max-age=0');
                header("Content-Transfer-Encoding: binary ");

                $writer=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
                $writer = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $writer->setOffice2003Compatibility(true);
                $writer->save('php://output');

            }
            exit;
        }

    }



?>
