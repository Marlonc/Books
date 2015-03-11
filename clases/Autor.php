<?php
	
	class Autor
	{
		private $CodAutor;
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
            $consulta = mysql_query("SELECT * FROM autor WHERE CodAutor=$id");
            $query = mysql_fetch_row($consulta);
            $this->CodAutor = $query[0];
            $this->Nombre = $query[1];
        }
        public function getCodAutor(){return $this->CodAutor;}
		public function getNombre(){return $this->Nombre;}
			
	}
	class Autores
	{
		public static function crea($nombre){
			$autor = strtoupper($nombre);
			$busqueda = mysql_query("SELECT * FROM autor WHERE Nombre ='$autor'");
			$total = mysql_num_rows($busqueda);
			if($total > 0 )
				return false;
			else{
				mysql_query("INSERT INTO autor (nombre) VALUES ('$autor')");
				$query = mysql_query("SELECT * FROM autor WHERE Nombre ='$autor'");
				
				$data =  mysql_fetch_row($query);
				
				return '<option value="'.$data[0].'"> '.$data[1].'</option>';
			}
		}

		public static function asignar($CodLibro,$Autores){
			foreach($Autores as $row){
                mysql_query("INSERT INTO autor_libro (CodAutor,CodLibro) VALUES ('$row','".$CodLibro."')");
            }
		}
		public static function editar($CodLibro,$Autores){
			self::eliminar($CodLibro);
			self::asignar($CodLibro,$Autores);
		}

		public static function eliminar($CodLibro){
			mysql_query("DELETE FROM autor_libro WHERE CodLibro='$CodLibro'");
		}
		public static function Todo(){
			$sql = mysql_query("SELECT * FROM autor");
			return $sql;
		}

		public static function existe_en($CodLibro,$autor){
			$autores = self::where($CodLibro);
			while($fila = mysql_fetch_array($autores)){
			  if($autor == $fila['CodAutor']){
			    return true;
			  }
			}
			return false;
		}

		public static function where($CodLibro){
			return mysql_query("SELECT * FROM autor_libro WHERE CodLibro ='$CodLibro'");
		}

		public static function libro($CodLibro){
			$query = mysql_query("SELECT * FROM autor_libro WHERE CodLibro ='$CodLibro'");
			while ($fila = mysql_fetch_array($query)) {
				$data[] = $fila;
			}
			return $data;
		}
	}
?>