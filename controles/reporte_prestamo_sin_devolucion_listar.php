<section class="content-header">
    <h1>Reportes</h1>
</section>
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Sin devolucion</h3></div>
</div>
<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
} 
require_once("clases/Prestamo.php");
require_once("clases/Libro.php");
require_once("clases/Paginacion.php");
$paginacion = new Paginacion('prestamo', 3);
  $paginacion->setWhere(array(array('Estado','=',0 )));
  $paginacion->setOrderBy(array('CodPrestamo','desc'));
   if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
    $query = $paginacion->getRecords($pagina);

  }else{
    $query = $paginacion->getRecords(1);
  }
?>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>Usuario</th>
      <th>Libro</th>
      <th>Prestamo</th>
      <th>Devolución Estimada</th>
    </tr>
  </thead>
  <tbody>
  <?php 
   if ($query == false) {
   echo '<tr><td colspan="6">No se encontraron resultados</td>';
 }else{
while ($fila = mysql_fetch_array($query)) {
	$prestamo = new Prestamo($fila['CodPrestamo']);
 ?>
 <tr>
    <td><?=$fila['CodPrestamo']?></td>
    <td><?php 
    $arregloUsuario = new Usuario($fila['CodUsuario']);
    echo $arregloUsuario->getNombre().' '.$arregloUsuario->getApellido();
    ?></td>
    <td>
    <?php
    $arregloLibro = new CopiaLibro($fila['CodCopia']);
    $libro = new Libro($arregloLibro->getCodLibro());
    ?>
    <a href="index.php?menu=obras_detalle&obra=<?=$arregloLibro->getCodLibro()?>" class="thumbnail" align="center">
    <?php 
    echo '<img src="'.$libro->getImagen().'" width="30px" height="30px"><span class="label label-info">'.$libro->getTitulo().'</span>';
    ?>
    </a>
    </td>
    <td>
    <?=$fila['Fecha']?>
    </td>
    <td>
    <?php 
    if($prestamo->getEstado()==0){
      ?>
    <div class="progress">
    <?php
      $porcentaje = (7-$prestamo->dias_transcurridos())*100/7;
      if($porcentaje>100 || $porcentaje<0){
        $porcentaje = 100;
      }
      if($prestamo->dias_transcurridos()>7 || $prestamo->dias_transcurridos()<0)
      {
        $style = 'danger';
      }elseif($prestamo->dias_transcurridos()>=2 && $prestamo->dias_transcurridos()<=4){
        $style = 'warning';
      }else{
        $style = 'success';
        }
    ?>
      <div class="progress-bar progress-bar-<?=$style?> progress-bar-striped" style="width: <?=$porcentaje?>%;"></div></div>
      <small>Quedan: </small><span class="label label-info"><?=$prestamo->dias_transcurridos();?> día(s)</span>
<?php }else{
  echo '<span class="label label-success">Prestamo entregado</span>';
  } ?>
    <br><small>Fecha: </small><span class="label label-info"><?=$prestamo->calculaFechaDevolucion()?></span><br>
    </td>
    <td> 
    <td> 
      <a href="index.php?menu=prestamo_eliminar&id=<?=$fila['CodPrestamo']?>" class="btn btn-danger btn-xs">Eliminar</a>
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
    $active = ($paginacion->getCurrentPage()==$i) ? 'class="active disabled"':'';
    echo '<li '.$active.'><a href="index.php?menu=reporte_prestamo_sin_devolucion_listar&pagina='.$i.'" >'.$i.'</a></li>';
  }
?>
</ul>
</section>