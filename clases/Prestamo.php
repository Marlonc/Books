<?php
	require_once("Libro.php");
	require_once("Devolucion.php");
	class Prestamo{
		private $codDevolucion;
		private $codPrestamo;
		private $codUsuario;
		private $codCopia;
		private $fecha;
		private $estado;
		
        function __construct() 
        { 
            $a = func_get_args(); 
            $i = func_num_args(); 
            if (method_exists($this,$f='__construct'.$i)) { 
                call_user_func_array(array($this,$f),$a); 
            } 
        } 
		function __construct1($id){
			$consulta = mysql_query("select *from prestamo where codPrestamo=$id");
			$query = mysql_fetch_row($consulta);
			$this->codPrestamo = $query[0];
			$this->codUsuario = $query[3];
			$this->codCopia = $query[2];
			$this->fecha = $query[1];
			$this->estado = $query[4];
		}

		function getcodPrestamo(){return $this->codPrestamo;}
		function getcodUsuario(){return $this->codUsuario;}
		function getcodCopia(){return $this->codCopia;}
		function getfecha(){return $this->fecha;}
		function getestado(){return $this->estado;}

		function setestado($estado){$this->estado = $estado;}

		function modifica_prestamo(){
			$consulta = mysql_query("update prestamo set Fecha = '$this->fecha',
			CodUsuario = '$this->codUsuario', CodCopia = '$this->codCopia',Estado = '$this->estado' where CodPrestamo = '$this->codPrestamo'");
			return $consulta;
		}
		function elimina_prestamo(){
			$consulta = mysql_query("update prestamo set Estado = 0
			where CodPrestamo = '$this->id'");
			return $consulta;
		}

		
		
		function calculaFechaDevolucion(){
			$nuevafecha = strtotime( '+7 day' , strtotime( $this->fecha )) ;	
			$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
			$verifico = date('D',strtotime($nuevafecha));
			if($verifico == 'Sun'){ 
				$nuevafecha = strtotime( '+1 day' , strtotime( $nuevafecha )) ;	
				$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
			}
			return $nuevafecha;
		}

		function dias_transcurridos()
		{
			$dias	= (strtotime($this->calculaFechaDevolucion())-strtotime(date('Y-m-d')))/86400;
					
			return $dias;
		}
	}
	class Prestamos
		{
			private $total = 4;
			public static function creaprestamo($codUsuario,$CodCopia,$fechaRegistro,$estado){
				
				$fechaConvertida = str_replace('/', '-', $fechaRegistro);
            	$fecha = date("Y-m-d",strtotime($fechaConvertida));
				$consulta = mysql_query("Insert into prestamo(CodUsuario,CodCopia,fecha,estado) Values (
				'$codUsuario','$CodCopia','$fecha',$estado)");
				$copia = new CopiaLibro($CodCopia);
				$copia->setDisponible(0);
				$copia->modificarCopiaLibro();
				return $consulta;
			}
			public static function activos(){
				return mysql_query("select *from prestamo");
			}

			public static function PrestamosActivos(){
				$query = mysql_query("SELECT * FROM prestamo ORDER BY CodPrestamo desc");
				return $query;
			}

			public static function PrestamosSinDevolucion(){
				$query = mysql_query("SELECT * FROM prestamo WHERE Estado=0 ORDER BY CodPrestamo desc");
				return $query;
			}

			public static function PrestamoDetalle($id){
				$query = mysql_query("SELECT * FROM prestamo WHERE CodPrestamo=$id");
				return mysql_fetch_array($query);
			}

			public static function where($atributo,$condicion,$valor){
				$query = mysql_query("SELECT * FROM prestamo WHERE ".$atributo.$condicion.$valor);
				return $query;
			}

			public static function whereCodUsuario($codigo){
				$query = mysql_query("SELECT * FROM prestamo WHERE CodUsuario=".$codigo);
				return $query;
			}

			public static function TopFecha($Top,$fecha){
				$query = mysql_query("SELECT * FROM prestamo WHERE Fecha='$fecha'  ORDER BY CodPrestamo DESC LIMIT ".$Top);
				return $query;
			}
			public static function Top($Top){
				$query = mysql_query("SELECT * FROM prestamo ORDER BY CodPrestamo DESC LIMIT ".$Top);
				return $query;
			}

			public static function TopUsuario($Top,$CodUsuario){
			$query = mysql_query("SELECT * FROM prestamo ORDER BY CodPrestamo WHERE CodUsuario = '$CodUsuario' DESC LIMIT ".$Top);
			return $query;
		}
			
		}
?>