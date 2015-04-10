<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "../error.php";</script>';
}
require_once("clases/Usuario.php");
require_once("clases/Paginacion.php");
$paginacion = new Paginacion('usuario',10);
$paginacion->setOrderBy(array('CodUsuario', 'DESC'));
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
    <div class="box-header"><h3 class="box-title">Listas usuario</h3>
    <span class="label label-danger">Total de registros: <?=$paginacion->getTotal()?></span>
    </div>
</div>
<table class="table table-hover ">
  <thead>
    <tr>
      <th>Codigo</th>
      <th>Nombres</th>
      <th>Apellidos</th>
      <th>Estado</th>
    </tr>
  </thead>
  <tbody>
<?php
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
  ?>
    
  </tbody>
</table> 
<ul class="pagination">
<?php
  for ($i=1; $i <= $paginacion->getPages(); $i++) { 
    $active = ($paginacion->getCurrentPage()==$i) ? ' class="active"':'';
    echo '<li'.$active.'><a href="index.php?menu=usuario_listar&pagina='.$i.'" >'.$i.'</a></li>';
  }
?>
</ul>
</section>