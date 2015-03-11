<?php 
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once('clases/Libro.php');
require_once('clases/Autor.php');
require_once('clases/Editorial.php');
if($_POST)
{
  $casilla = $_POST['casilla'];
  foreach ($casilla as $fila) {
    mysql_query("UPDATE libro SET Estado = 0 WHERE codLibro =".$fila);
  }
}
$query = Libros::librosActivos();

?>
<section class="content-header">
    <h1>Obras</h1>
</section>
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Borrar obras</h3></div>
</div>
<form method="post">
  <table class="table table-striped table-hover ">
    <thead>
      <tr>
        <th></th>
        <th>#</th>
        <th>Titulo</th>
        <th>Autor</th>
        <th>Editorial</th>
        <th>Fecha</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php 
  while ($fila = mysql_fetch_array($query)) {
   ?>
   <tr>
      <td><input type="checkbox" name="casilla[]" value="<?=$fila['CodLibro']?>"></td> 
      <td><?=$fila['CodLibro']?></td>
      <td><?=$fila['Titulo']?></td>
      <td>
      <?php
        $autores = Autores::where($fila['CodLibro']);
        while ($autor = mysql_fetch_array($autores)) {
          $a = new Autor($autor['CodAutor']);
          echo '<span class="label label-success">'.$a->getNombre().'</span> ';
        }
      ?>
      </td>
      <td>
      <?php
        $editorial = new Editorial($fila['CodEditorial']);
        echo $editorial->getNombre();
      ?>
      </td>
      <td><?=$fila['fecha_registro']?></td>
      <td> 
        <a href="index.php?menu=obras_editar&id=<?=$fila['CodLibro']?>" class="btn btn-default btn-xs">Editar</a>
      </td>
    </tr>
   <?php
  }
    ?>
    </tbody>
  </table> 
<button class="btn btn-danger">Eliminar</button>
</form>
</section>
<link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<script src="js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
 <script type="text/javascript">
    $(function() {
     
        $('.table').dataTable();
    });
</script>
