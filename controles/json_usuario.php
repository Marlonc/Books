<?php
	require("includes/config.php");
	require_once("clases/Prestamo.php");
	require_once("clases/CopiaLibro.php");
	require_once("clases/Libro.php");
	
	if(isset($_POST['usuario'])){

		$respuesta = Prestamos::whereCodUsuario($_POST['usuario']);
		$res = array();	
		if (!$respuesta) {
			$res[] = array(0, 'No hay ningun prestamo de este usuario');
		}else{
			while($fila=mysql_fetch_array($respuesta)){
                $copia = new CopiaLibro($fila['CodCopia']);
                $libro = new Libro($copia->getCodLibro());
                $res[] = array($fila['CodPrestamo'], $libro->getTitulo().' ['.$fila['Fecha'].']');
            }
			
		}
		print json_encode($res);
	}

?>