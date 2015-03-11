<?php
class Mora{
	private $CodMora;
	private $FechaInicio;
	private $FechaFin;
	private $CodPrestamo;
	private $Observacion;
	private $Estado;
	
	function __construct() 
        { 
            $a = func_get_args(); 
            $i = func_num_args(); 
            if (method_exists($this,$f='__construct'.$i)) { 
                call_user_func_array(array($this,$f),$a); 
            } 
        } 
	function __construct1($id){
            $consulta = mysql_query("SELECT * FROM mora WHERE CodMora=$id");
            $query = mysql_fetch_row($consulta);
            $this->CodMora = $query[0];
            $this->FechaInicio = $query[1];
            $this->FechaFin = $query[2];
            $this->CodPrestamo= $query[3];
            $this->Observacion = $query[4];
			$this->Estado= $query[5];
                 
        }
		 public function getCodMora(){return $this->CodMora;}
       	 public function getFechaInicio(){$fecha = date("d-m-Y",strtotime($this->FechaInicio));
            $fechaConvertida = str_replace('-', '/', $fecha);
            return $fechaConvertida;}
	 	 public function getFechaFin(){$fecha = date("d-m-Y",strtotime($this->FechaFin));
            $fechaConvertida = str_replace('-', '/', $fecha);
            return $fechaConvertida;}
	 	 public function getCodPrestamo(){return $this->CodPrestamo;}
	 	 public function getObservacion(){return $this->Observacion;}
         public function getEstado(){return $this->Estado;}
		 
		 
		 
        public function setFechaInicio($FechaInicio){
            $fechaConvertida = str_replace('/', '-', $FechaInicio);
            $fecha = date("Y-m-d",strtotime($fechaConvertida));
            $this->FechaInicio = $fecha;}
	    public function setFechaFin($FechaFin){
            $fechaConvertida = str_replace('/', '-', $FechaFin);
            $fecha = date("Y-m-d",strtotime($fechaConvertida));
            $this->FechaFin = $fecha;}	
		public function setCodPrestamo($CodPrestamo){$this->CodPrestamo = $CodPrestamo;}
		public function setObservacion($Observacion){$this->Observacion = $Observacion;}
		public function setEstado($estado){$this->Estado = $Estado;}
		
		public function modificaLibro(){
            $query = mysql_query("UPDATE mora SET FechaInicio='$this->FechaInicio' , FechaFin='$this->FechaFin' , CodPrestamo ='$this->CodPrestamo' , Observacion='$this->Observacion' WHERE CodMora ='$this->CodMora'");
            return $query;
        }

        public function borrarLibro(){
            $query = mysql_query("UPDATE mora SET Estado=0 WHERE CodMora ='$this->CodMora'");
            return $query;
        }		
	}

	class Moras{
	
		public static function crea($FechaInicio,$FechaFin,$CodPrestamo,$Observacion,$Estado){
			$consulta = mysql_query("Insert into mora(FechaInicio,FechaFin,CodPrestamo,Observacion) Values (
			'$FechaInicio','$FechaFin','$CodPrestamo','$Observacion')");
			return mysql_insert_id();
		}
	}

?>