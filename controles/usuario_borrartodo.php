<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
if(isset($_GET['opcion']) && $_GET['opcion']=='si')
{
	$q = mysql_query("UPDATE usuario SET Estado =0");
	if (!$q) {
		mysql_error();
	}
	echo '<div class="alert alert-success">Se han eliminado todos los registros</div>';
}	
?>
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Borrar Usuarios</h3></div>
</div>
 <div class="alert alert-danger">¿Esta seguro que desea eliminar todos los usuario?</div>
 <div class="col-lg-12">
    <div class="radio">
      <label>
        <input type="radio" name="opcion" id="optionsRadios1" value="si" checked="">
        SI
      </label>
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="opcion" id="optionsRadios2" value="no">
        NO
      </label>
    </div>
 <a class="btn btn-success" href="index.php?menu=usuario_borrartodo&opcion=si">Eliminar TODO</a> <a href="index.php" class="btn btn-default">Cancelar</a>
 </div>
</section>
