<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once("clases/Libro.php");
require_once("clases/Autor.php");
require_once("clases/Editorial.php");
require_once("clases/Paginacion.php");
$paginacion = new Paginacion('usuario', 10);
$paginacion->setOrderBy(array('CodUsuario','desc'));
$urldni="";
$urlnombre="";
$urlapellido="";
$data = array();
if(isset($_GET['dni']) && $_GET['dni']!= ""){
	$data[] = array('DNI',' LIKE ','"%'.$_GET['dni'].'%"');
	$urldni = "&dni=".$_GET['dni'];
}
if(isset($_GET['nombre']) && $_GET['nombre'] != 0){
	$data[] = array('Nombres',' LIKE ','"%'.$_GET['nombre'].'%"');
	$urlnombre = "&nombre=".$_GET['nombre'];
}
if(isset($_GET['apellido']) && $_GET['apellido']!= ""){
	$data[] = array('Apellido',' LIKE ','"%'.$_GET['apellido'].'%"');
}


if(isset($_POST['dni']) && $_POST['dni']!= ""){
	$data[] = array('dni',' LIKE ','"%'.$_POST['dni'].'%"');
	$urldni = "&dni=".$_POST['dni'];
}
if(isset($_POST['nombre']) && $_POST['nombre'] != ""){
	$data[] = array('Nombres',' LIKE ','"%'.$_POST['nombre'].'%"');
	$urlnombre = "&nombre=".$_POST['nombre'];
}
if(isset($_POST['apellido']) && $_POST['apellido']!= ""){
	$data[] = array('Apellido',' LIKE ','"%'.$_POST['apellido'].'%"');
	$urlapellido = "&apellido=".$_POST['apellido'];
}
if(count($data)>0){
$paginacion->setWhere($data);
}
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
    $query = $paginacion->getRecords($pagina);

  }else{
    $query = $paginacion->getRecords(1);
  }
?>
<section class="content-header">
    <h1>Usuarios</h1>
</section>
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Buscar usuarios</h3>
    </div>
</div>
<div class="row">
	<div class="col-sm-3">
		<form method="post" action="index.php?menu=usuario_buscar">
			<div class="form-group">
				<label>Por Codigo</label>
				<input class="form-control" name="dni" placeholder="Codigo">	
			</div>
			<div class="form-group">
				<label>Por Nombre</label>
				<input class="form-control" name="nombre" placeholder="Nombre">
			</div>
			<div class="form-group">
		      	<label for="apellido">Por Apellido</label>
		      	<input class="form-control" name="apellido" placeholder="Apellido">
		    </div>
		    <button class="btn btn-success">Buscar</button>
		</form>
	</div>
	<div class="col-sm-9">
		<table class="table table-hover ">
		  <thead>
		    <tr>
		      <th>Codigo</th>
		      <th>Nombre</th>
		      <th>Apellido</th>
		      <th>Estado</th>
		      <th></th>
		    </tr>
		  </thead>
		  <tbody>
		  <?php 
		  
	if (isset($query)) {
		if($paginacion->getTotal()>0){
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
		    <td><?=$fila['DNI']?></td>
		    <td><?=$fila['Nombres']?></td>
		    <td><?=$fila['Apellido']?></td>
		    <td><?php
		     if($fila['Estado']==0) {
		      echo 'No Activo'; 
		   } else { 
		      echo 'Activo';
		   }
		    ?></td>
		    <td> 
		      <a href="index.php?menu=usuario_editar&id=<?=$fila['CodUsuario']?>" class="btn btn-default btn-xs">Editar</a>
		    </td>
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
		    echo '<li '.$active.'><a href="index.php?menu=usuario_buscar'.$urldni.$urlnombre.$urlapellido.'&pagina='.$i.'" >'.$i.'</a></li>';
		  }
		?>
		</ul>
	</div>
</div>
</section>