<?php
	require("includes/config.php");
	require_once("clases/Autor.php");
	if(isset($_POST['nombre'])){
		$respuesta = Autores::crea($_POST['nombre']);	
		echo $respuesta;
	}

?>