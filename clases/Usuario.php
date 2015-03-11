<?php

    class Usuario
    {
    	private $cod;
    	private $DNI;
    	private $nombre;
    	private $apellido;
    	private $estado;
        private $clave;
        private $tipo;

        private $cantidadPrestamos = 3;

        private $rangos = array(
            1 => 'Administrador',
            2 => 'Personal',
            3 => 'Cliente' );

        function __construct($id){
            $consulta = mysql_query("SELECT * FROM usuario WHERE CodUsuario=$id");
            $query = mysql_fetch_row($consulta);
            $this->cod = $query[0];
            $this->DNI = $query[1];
            $this->nombre = $query[2];
            $this->apellido = $query[3];
            $this->estado = $query[4];
            $this->clave = $query[5];
            $this->tipo = $query[6];
        }
        public function getCod(){return $this->cod;}
        public function getDNI(){return $this->DNI;}
        public function getNombre(){return $this->nombre;}
        public function getApellido(){return $this->apellido;}
		public function getEstado(){return $this->estado;}
        public function getClave(){return $this->clave;}
        public function getTipo(){return $this->tipo;}

        public function setNombre($nombre){$this->nombre = $nombre;}
        public function setApellidos($apellido){$this->apellido = $apellido;}
        public function setDNI($DNI){$this->DNI = $DNI;}
        public function setEstado($Estado){$this->estado=$Estado;}
        public function setClave($clave){$this->clave=md5($clave);}
        public function setTipo($Tipo){$this->tipo=$Tipo;}

		public function modificaUsuario(){
            $query = mysql_query("UPDATE usuario SET Nombres='$this->nombre', Apellido='$this->apellido', DNI = '$this->DNI', Estado = '$this->estado', Tipo = '$this->tipo' WHERE codUsuario ='$this->cod'");
            return $query;
        }

        public function borrarUsuario(){
            $query = mysql_query("UPDATE usuario SET Estado=0 WHERE codUsuario ='$this->cod'");
            return $query;
        }

        public function prestamos(){
            $query = Prestamos::where('CodUsuario','=',$this->cod);
            return $query;
        }

        public function LimitePrestamo(){
            $prestamos = mysql_num_rows($this->prestamos());
            if($prestamos<=$this->cantidadPrestamos){
                return true;
            }
            return false;
        }

    }
    
    class Usuarios{

        public static function valida($usuario,$clave){
            $hash = md5($clave);
            $query = mysql_query("SELECT * FROM usuario WHERE DNI = '$usuario' and Clave = '$hash'");
            if(mysql_num_rows($query)>0){
                $user = mysql_fetch_row($query);
                $usuario = new Usuario($user[0]);
                $_SESSION['usuario'] = array('dni' => $usuario->getDNI(), 'tipo' =>$usuario->getTipo(), 'CodUsuario' => $usuario->getCod());
                return true;
            }
            return false;
        }
        public static function UsuariosActivos(){
            $query = mysql_query("SELECT * FROM usuario WHERE Estado = 1 and Tipo=3");
            return $query;
        }
        public static function crea($dni,$nombre,$apellido,$estado,$password,$tipo){
            $hash = md5($password);
            $consulta = mysql_query("INSERT INTO usuario (DNI,Nombres,Apellido,Estado,Clave,Tipo) VALUES ('$dni','$nombre','$apellido','$estado','$hash','$tipo')");
            return $consulta;
        }

        public static function todos(){
            return mysql_query("SELECT * FROM usuario WHERE Tipo=3");
        }

        public static function busca($dni){
            $query = mysql_query("SELECT * FROM usuario WHERE DNI=$dni");

            return false;
        }

    }

?>