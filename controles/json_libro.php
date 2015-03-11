<?php
	require("includes/config.php");
	require_once("clases/Libro.php");
	if(isset($_POST['libro'])){
		$respuesta = Libros::CopiaDisponible($_POST['libro']);	
		 while($fila=mysql_fetch_assoc($respuesta)){
                $res[] = $fila;
            }
		print json_encode($res);
	}

?>