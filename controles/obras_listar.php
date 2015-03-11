<?php 
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once('clases/Libro.php');
require_once('clases/Autor.php');
require_once('clases/Editorial.php');
require_once("clases/Paginacion.php");
// --> includes/funciones.php
//$query = Usuarios::todos();
// --> includes/funciones.php
//$query = Libros::librosActivos();
  $paginacion = new Paginacion('libro', 10);
  $paginacion->setOrderBy(array('CodLibro','desc'));
  
   if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
    $query = $paginacion->getRecords($pagina);

  }else{
    $query = $paginacion->getRecords(1);
  }
  
?>
<section class="content-header">
    <h1>Obras</h1>
</section>
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Listas obras</h3>
    <span class="label label-danger">Total de registros: <?=$paginacion->getTotal()?></span>
    </div>
</div>
<table class="table table-hover">
  <thead>
    <tr>
      <th>Imagen</th>
      <th>#</th>
      <th>Titulo</th>
      <th>Autor</th>
      <th>Editorial</th>
      <th>Fecha</th>
    </tr>
  </thead>
  <tbody>
  <?php 
  
 if ($query == false) {
   echo '<tr><td colspan="6">No se encontraron resultados</td>';
 }else{
while ($fila = mysql_fetch_array($query)) {
 ?>
 <tr
<?php
   if($fila['Estado']==0) {
    echo 'class="danger"'; 
 } else{
  echo 'class="success"';
 }
  ?>
 >
    <td>
      <a href="index.php?menu=obras_detalle&obra=<?=$fila['CodLibro']?>">
        <img src="imagenes/<?=$fila['imagen']?>" width="60px" class="thumbnail">
      </a>
    </td>
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
    <td>
      <span class="label label-info"><?=$fila['fecha_registro']?></span>
    </td>
    <td> 
      <a href="index.php?menu=obras_editar&id=<?=$fila['CodLibro']?>" class="btn btn-default btn-xs">Editar</a>
    </td>
  </tr>
 <?php
}}
  ?>
    
  </tbody>
</table> 
<ul class="pagination">
<?php

  for ($i=1; $i <= $paginacion->getPages(); $i++) { 
    $active = ($paginacion->getCurrentPage()==$i) ? 'class="active"':'';
    echo '<li '.$active.'><a href="index.php?menu=obras_listar&pagina='.$i.'" >'.$i.'</a></li>';
  }
?>
</ul>
</section>