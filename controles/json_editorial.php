<?php
	require("includes/config.php");
	require_once("clases/Editorial.php");
	if(isset($_POST['nombre'])){
		$respuesta = Editoriales::crea($_POST['nombre']);	
		echo $respuesta;
	}

?>