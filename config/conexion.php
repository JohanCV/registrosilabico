<?php
session_start();

class Conectar {
   	public $dbh;

   	public function conexion(){
     		try {
     			//$conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=registrosilabico","root","");
            $conectar = $this->dbh = new PDO("mysql:local=10.0.0.3;dbname=siac","dutic","dutic2019");
    		     return $conectar;

     		} catch (Exception $e) {
     			print "¡Error!: " . $e->getMessage() . "<br/>";
                die();
     		}
  	}


  	public function set_names(){
  	 	 return $this->dbh->query("SET NAMES 'utf8'");
  	}

  	public function ruta_aulavirtual(){
  	  	return "http://190.119.145.175/miaula/";
  	}

  	//Ruta para el localhost
  	public function ruta(){
        return "http://localhost/proyecto/registrosilabico/";
    }
}
?>
