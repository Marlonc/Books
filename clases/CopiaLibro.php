<?php
require_once("Libro.php");
	class CopiaLibro{
		private $CodCopia;
		private $CodLibro;
		private $Estado;
		private $Disponible;

        function __construct() 
        { 
            $a = func_get_args(); 
            $i = func_num_args(); 
            if (method_exists($this,$f='__construct'.$i)) { 
                call_user_func_array(array($this,$f),$a); 
            } 
        } 

		function __construct1($id){
			$consulta = mysql_query("SELECT * FROM copialibro where CodCopia='$id'");
			$query = mysql_fetch_row($consulta);
			$this->CodCopia = $query[0];
			$this->CodLibro = $query[1];
			$this->Estado = $query[2];
			$this->Disponible = $query[3];
		}

		public function getCodCopia(){return $this->CodCopia;}
		public function getCodLibro(){return $this->CodLibro;}
		public function getEstado(){return $this->Estado;}
		public function getDisponible(){return $this->Disponible;}
		
	    public function setEstado($Estado){$this->estado = $Estado;}
		public function setDisponible($Disponible) {$this->Disponible=$Disponible;}
		
		 public function modificarCopiaLibro(){
            $query = mysql_query("UPDATE copialibro SET Estado='$this->Estado' , Disponible='$this->Disponible' WHERE CodCopia ='$this->CodCopia'");
            return $query;
        }

	}
	class CopiaLibros{
		public static function where($atributo,$condicion,$valor){
			$query = mysql_query("SELECT * FROM copialibro WHERE ".$atributo.$condicion.$valor." ");
			return $query;
		}
		public static function crea($CodLibro,$cantidad){
			for ($i=0; $i < $cantidad; $i++) { 
				mysql_query("INSERT INTO copialibro (CodLibro,Estado,Disponible) VALUES ('$CodLibro','Activo',1)");
			}
		}
	}
?>