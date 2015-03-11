<?php
	require_once('Prestamo.php');
    require_once('Usuario.php');
	require_once('Mora.php');
	require_once('CopiaLibro.php');
    class Devolucion
    {
    	private $codDevolucion;
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
            $consulta = mysql_query("SELECT * FROM devolucion WHERE codDevolucion=$id");
            $query = mysql_fetch_row($consulta);
            $this->codDevolucion = $query[0];
            $this->fecha = $query[1];
            $this->estado = $query[2];
        }
       
        public function getcodDevolucion(){return $this->codDevolucion;}
        public function getfecha(){return $this->fecha;}
        public function getEstado(){return $this->estado;}
        public function getFechaRegistro(){
            $fecha = date("d-m-Y",strtotime($this->fechaRegistro));
            $fechaConvertida = str_replace('-', '/', $fecha);
            
            return $fechaConvertida;
        }
                public function setEstado($estado){$this->estado = $estado;}
        public function setfecha($fechaRegistro){
             $fechaConvertida = str_replace('/', '-', $fechaRegistro);
            $fecha = date("Y-m-d",strtotime($fechaConvertida));
            $this->fechaRegistro = $fecha;}
        public function borrarDevolucion(){
            $query = mysql_query("UPDATE devolucion SET Estado=0 WHERE CodDevolucion ='$this->codDevolucion'");
            return $query;
        }
    }

     class Devoluciones{
        public static function activos(){
            return mysql_query("SELECT  * FROM  devolucion WHERE Estado = 1");
        }
        public static function crea($CodPrestamo,$fechaRegistro,$estado){
            $fechaConvertida = str_replace('/', '-', $fechaRegistro);
            $fecha = date("Y-m-d",strtotime($fechaConvertida));
            mysql_query("INSERT INTO devolucion (CodPrestamo,Fecha,Estado) VALUES ('$CodPrestamo','$fecha','$estado')");
			$prestamo = new Prestamo($CodPrestamo);
			$copia = new CopiaLibro($prestamo->getCodCopia());

			$copia->setDisponible(1);
			$copia->modificarCopiaLibro(); 

            $prestamo->setestado(1);
            $prestamo->modifica_prestamo();

			$fecha1 = strtotime($fecha);
			$fecha2 = strtotime($prestamo->calculaFechaDevolucion());
			if($fecha2<$fecha1){
				$nuevafecha = strtotime( '+7 day' , strtotime( $fecha )) ;	
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
				Moras::crea($fecha,$nuevafecha,$CodPrestamo,'Fecha de entrega supero el limite',1);
				$usuario = new Usuario($prestamo->getCodUsuario());
				$usuario->setEstado(0);
				$usuario->modificaUsuario();
			}
            return mysql_insert_id();
        }
        public static function where($atributo,$condicion,$valor){
            $query = mysql_query("SELECT * FROM devolucion WHERE ".$atributo.$condicion.$valor);
            return $query;
        }

    }
     
?>