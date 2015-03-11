<?php
	class Editorial
	{
		private $CodEditorial;
		private $Nombre;
		function __construct() 
        { 
            $a = func_get_args(); 
            $i = func_num_args(); 
            if (method_exists($this,$f='__construct'.$i)) { 
                call_user_func_array(array($this,$f),$a); 
            } 
        } 
		function __construct1($id){
            $consulta = mysql_query("SELECT * FROM editorial WHERE CodEditorial=$id");
            $query = mysql_fetch_row($consulta);
            $this->CodEditorial = $query[0];
            $this->Nombre = $query[1];
        }
        public function getCodEditorial(){return $this->CodEditorial;}
		public function getNombre(){return $this->Nombre;}
			
	}
	class Editoriales
	{
		public static function crea($nombre){
			$editorial = strtoupper($nombre);
			$busqueda = mysql_query("SELECT * FROM editorial WHERE Nombre ='$editorial'");
			$total = mysql_num_rows($busqueda);
			if($total > 0 )
				return false;
			else{
				mysql_query("INSERT INTO Editorial (nombre) VALUES ('$editorial')");
				$query = mysql_query("SELECT * FROM editorial WHERE Nombre ='$editorial'");
				
				$data =  mysql_fetch_row($query);
				
				return '<option value="'.$data[0].'"> '.$data[1].'</option>';
			}
		}


		public static function Todo(){
			$sql = mysql_query("SELECT * FROM editorial");

			return $sql;
		}
	}

?>