<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once("clases/Libro.php");
require_once("clases/Autor.php");
require_once("clases/Editorial.php");
require_once("clases/Paginacion.php");
$paginacion = new Paginacion('libro', 3);
$paginacion->setOrderBy(array('CodLibro','desc'));
$urltitulo="";
$urleditorial="";
$urlfecha="";
$data = array();
if(isset($_GET['titulo']) && $_GET['titulo']!= ""){
	$data[] = array('Titulo',' LIKE ','"%'.$_GET['titulo'].'%"');
	$urltitulo = "&titulo=".$_GET['titulo'];
}
if(isset($_GET['editorial']) && $_GET['editorial'] != 0){
	$data[] = array('CodEditorial','=',$_GET['editorial']);
	$urleditorial = "&editorial=".$_GET['editorial'];
}
if(isset($_GET['fecha']) && $_GET['fecha']!= ""){
	$fechaConvertida = str_replace('/', '-', $_GET['fecha']);
    $fecha = date("Y-m-d",strtotime($fechaConvertida));
	$data[] = array('fecha_registro','=','"'.$fecha.'"');
	$urlfecha = "&fecha=".$fecha;
}


if(isset($_POST['titulo']) && $_POST['titulo']!= ""){
	$data[] = array('Titulo',' LIKE ','"%'.$_POST['titulo'].'%"');
	$urltitulo = "&titulo=".$_POST['titulo'];
}
if(isset($_POST['editorial']) && $_POST['editorial'] != 0){
	$data[] = array('CodEditorial','=',$_POST['editorial']);
	$urleditorial = "&editorial=".$_POST['editorial'];
}
if(isset($_POST['fecha']) && $_POST['fecha']!= ""){
	
	$fechaConvertida = str_replace('/', '-', $_POST['fecha']);
    $fecha = date("Y-m-d",strtotime($fechaConvertida));
	$data[] = array('fecha_registro','=','"'.$fecha.'"');
	$urlfecha = "&fecha=".$fecha;
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
    <h1>Obras</h1>
</section>
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Buscar obras</h3></div>
</div>
	<div class="row">
		<div class="col-sm-3">
			<form method="post" action="index.php?menu=obras_buscar">
				<div class="form-group">
					<label>Por titulo</label>
					<input class="form-control" name="titulo" placeholder="Que buscar">	
				</div>
				<div class="form-group">
					<label>Por Editorial</label>
					<select name="editorial" class="form-control chosen">
					<option value="0">Ninguno</option>
					<?php 
			          $editoriales = Editoriales::Todo();
			          while($fila = mysql_fetch_array($editoriales)){
			            	echo '<option value="'.$fila['CodEditorial'].'">'.$fila['Nombre'].'</option>';
			            }
			        ?>
					</select>
				</div>
				<div class="form-group">
			      	<label for="fecha">Fecha:</label>
			      	<input type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha">
			    </div>
			    <button class="btn btn-success">Buscar</button>
			</form>
		</div>
		<div class="col-sm-9">
			<table class="table table-hover ">
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
			    <td>
			    	<a href="index.php?menu=obras_detalle&obra=<?=$fila['CodLibro']?>"><img src="imagenes/<?=$fila['imagen']?>" width="60px" class="thumbnail"></a>
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
			    <td><?=$fila['fecha_registro']?></td>
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
			    echo '<li '.$active.'><a href="index.php?menu=obras_buscar'.$urltitulo.$urleditorial.$urlfecha.'&pagina='.$i.'" >'.$i.'</a></li>';
			  }
			?>
			</ul>
		</div>
	</div>
</section>