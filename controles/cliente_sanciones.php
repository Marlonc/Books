<section class="content-header">
    <h1>Mis Sanciones</h1>
</section>
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Lista de sancion</h3></div>
</div>
<?php
require_once("clases/Mora.php");
require_once("clases/Prestamo.php");
require_once("clases/paginacionv2.php");
$urldni="";
$paginacion = new Paginacionv2(array('mora','prestamo','copialibro','libro','usuario'), 3,
array(array('mora','prestamo','CodPrestamo'),
array('prestamo','copialibro','CodCopia'),
array('copialibro','libro','CodLibro'),
array('prestamo','usuario','CodUsuario')));
$paginacion->setOrderBy(array('CodMora','desc'));
$paginacion->setSelect(array('CodMora','Titulo','Fecha','FechaInicio','FechaFin','Observacion','copialibro.CodCopia','DNI'));
$paginacion->setWhere(array(array('prestamo.CodUsuario','=',$_SESSION['usuario']['CodUsuario'])));
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
    $query = $paginacion->getRecords($pagina);

  }else{
    $query = $paginacion->getRecords(1);
  }
?>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped table-hover ">
		  <thead>
		    <tr>
		      <th>#</th>
		      <th>Titulo</th>
              <th>Copia afectada</th>
		      <th>Fecha de Prestamo</th>
		      <th>Inicio de Mora</th>
		      <th>Fin de Mora</th>
              <th>Observacion</th>
		    </tr>
		  </thead>
		  <tbody>
		  <?php 
	if (isset($query)) {
		if($paginacion->getTotal()>0){
		while ($fila = mysql_fetch_array($query)) {
		 ?>
		 <tr>
		    <td><?=$fila[0]?></td>
		    <td><?=$fila['Titulo']?></td>
            <td><?=$fila['CodCopia']?></td>
		    <td><?=$fila['Fecha']?></td>
            <td><?=$fila['FechaInicio']?></td>
            <td><?=$fila['FechaFin']?></td>
            <td><?=$fila['Observacion']?></td>
		  </tr>
	<?php
			}
		}else{
			echo '<td colspan="6">No hay resultados con esos datos</td>';
		}
	}
	?>
		  </tbody>
		</table> 
		<ul class="pagination">
		<?php
		  for ($i=1; $i <= $paginacion->getPages(); $i++) { 
		    $active = ($paginacion->getCurrentPage()==$i) ? 'class="active disabled"':'';
		    echo '<li '.$active.'><a href="index.php?menu=mora_listar'.$urldni.'&pagina='.$i.'" >'.$i.'</a></li>';
		  }
		?>
		</ul>
	</div>
</div>
</section>