<?php
    require_once("CopiaLibro.php");
    require_once("Autor.php");
    require_once("fileManager.php");
    class Libro
    {
    	private $codLibro;
    	private $titulo;
    	private $CodEditorial;
    	private $estado;
        private $autores;
        private $fechaRegistro;
        private $imagen;

        private $error = 0;

        public $ubicacion = 'imagenes/';


        function __construct($id){
            $consulta = mysql_query("SELECT* FROM libro WHERE codLibro=$id");
            if(mysql_num_rows($consulta)>0){
                $query = mysql_fetch_row($consulta);
                $this->codLibro = $query[0];
                $this->titulo = $query[1];
                $this->CodEditorial = $query[2];
                $this->estado = $query[3];
                $this->fechaRegistro = $query[4];
                $this->imagen = $query[5];
                $this->autores = Autores::where($this->codLibro);
                $this->error = 0;
            }else{
                $this->error = 1;
            }
            
        }
        public function getError(){return $this->error;}
        public function getCodLibro(){return $this->codLibro;}
        public function getTitulo(){return $this->titulo;}
        public function getEditorial(){return $this->CodEditorial;}
        /*public function getEditorial(){
            $query = mysql_query("SELECT * FROM Editorial WHERE CodEditorial='$this->CodEditorial'");
            $data = mysql_fetch_row($query);
            return $data[1];
        }*/
        public function getEstado(){return $this->estado;}
        public function getFechaRegistro(){
            $fecha = date("d-m-Y",strtotime($this->fechaRegistro));
            $fechaConvertida = str_replace('-', '/', $fecha);
            return $fechaConvertida;
        }
        public function getAutores(){return $this->autores;}
        public function getImagen(){return $this->ubicacion.$this->imagen;}

        public function setTitulo($titulo){$this->titulo = $titulo;}
        public function setEditorial($CodEditorial){$this->CodEditorial = $CodEditorial;}
        public function setEstado($estado){$this->estado = $estado;}
        public function setFechaRegistro($fechaRegistro){
             $fechaConvertida = str_replace('/', '-', $fechaRegistro);
            $fecha = date("Y-m-d",strtotime($fechaConvertida));
            $this->fechaRegistro = $fecha;
        }
        public function setAutores($autores){
            Autores::editar($this->codLibro,$autores);
        }
        public function setImagen($imagen){$this->imagen = $imagen;}

        public function modificaLibro(){
            $query = mysql_query("UPDATE libro SET Titulo='$this->titulo' , CodEditorial='$this->CodEditorial' , Estado ='$this->estado' , fecha_registro='$this->fechaRegistro', imagen='$this->imagen' WHERE CodLibro ='$this->codLibro'");
            return $query;
        }

        public function borrarLibro(){
            $query = mysql_query("UPDATE libro SET Estado=0 WHERE CodLibro ='$this->codLibro'");
            return $query;
        }

        public function cantidadCopias(){
            $query = mysql_query("SELECT * FROM copialibro WHERE CodLibro=".$this->codLibro);
            return mysql_num_rows($query);
        }

        public function estaditicaAnual($year){
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
            for ($i=1; $i <= 12; $i++) { 
                $query = mysql_query("SELECT * FROM copialibro c INNER JOIN prestamo p ON c.CodCopia = p.CodCopia and c.CodLibro = '$this->codLibro' WHERE month(p.Fecha) = $i and year(p.Fecha) = $year ");
                $data[] = (object)array('year' => $meses[$i-1],'value' =>mysql_num_rows($query));
            }
            return $data;
        }

    }

     class Libros{
        public static function activos(){
            return mysql_query("SELECT  * FROM  libro WHERE Estado = 1");
        }
        public static function crealibro($titulo,$CodEditorial,$estado,$fechaRegistro,$autores,$imagen){
            $fechaConvertida = str_replace('/', '-', $fechaRegistro);
            $fecha = date("Y-m-d",strtotime($fechaConvertida));
            if(!empty($imagen['name'][0])){
                $aleatorio = time();
                $nombreImagen = $aleatorio.'-'.$imagen['name'];
                $imgUploader = new fileManager;
                $imgUploader->setFileName($nombreImagen);
                $imgUploader->upload($imagen);
            }else{
                $nombreImagen = 'blanco.jpg';
            }


            $consulta = mysql_query("INSERT INTO libro (Titulo,CodEditorial,Estado,fecha_registro,imagen) VALUES ('$titulo','$CodEditorial','$estado','$fecha','".$nombreImagen."')");
            $CodLibro = mysql_insert_id();

            //insertar autores
            Autores::asignar($CodLibro,$autores);
			//aqui va la funcion que aun no creo
			$platano = CopiaLibros::crea($CodLibro,1);//okay T-T
            return $CodLibro;
        }
        public static function where($atributo,$condicion,$valor){
            $query = mysql_query("SELECT * FROM libro WHERE ".$atributo.$condicion.$valor." ");
            return $query;
        }

        public static function Todo(){
            $query = mysql_query("SELECT * FROM libro");
            return $query;
        }

        public static function LibrosActivos(){
            $query = mysql_query("SELECT * FROM libro WHERE Estado=1");
            return $query;
        }

        public static function LibroDetalle($id){
            $query = mysql_query("SELECT * FROM libro WHERE CodLibro=$id");
            return mysql_fetch_array($query);
        }
        public static function CopiaDisponible($id){
            return mysql_query("SELECT * FROM copialibro WHERE CodLibro ='$id' and Disponible=1"); 
        }
        public static function all($year){
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
            for ($i=1; $i <= 12; $i++) { 
                $query = mysql_query("SELECT * FROM libro WHERE month(fecha_registro) = $i and year(fecha_registro) = $year");
                $data[] = (object)array('year' => $meses[$i-1],'value' =>mysql_num_rows($query));
            }
            return $data;
        }
    }
?>