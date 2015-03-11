<?php
require("clases/Autor.php");
if($_POST){
	foreach($_POST['autores'] as $row){
	echo $row.'<br>';
	}
}

?>
<form action="" method="post">
    <select multiple="multiple" name="autores[]">
    <?php 
            $autores = Autores::Todo();
            while($fila = mysql_fetch_array($autores)){
                echo '<option value="'.$fila['CodAutor'].'">'.$fila['Nombre'].'</option>';
                }
            ?>
    </select>
    <button>agregar</button>
</form>