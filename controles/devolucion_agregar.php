<section class="content-header">
    <h1>Devoluciones</h1>
</section>
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Agregar devolución</h3>
    </div>
<div class="box-body">
<?php 
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once("clases/Prestamo.php");
require_once("clases/Libro.php");
require_once("clases/Devolucion.php");
require_once("clases/Usuario.php");
if($_POST)
{
  $prestamo = $_POST['prestamo'];
  $fecha = date('d/m/Y');
  if (isset($prestamo) && isset($fecha) &&
    $prestamo != '' && $fecha != '') {
    $detalle = new Prestamo($prestamo);
  //if($fecha >= $detalle->getfecha())
  //{
    $resultado = Devoluciones::crea($prestamo,$fecha,1);
	
    if($resultado>0)
    	echo '<div class="alert alert-success">Devolucion agregada exitosamente</div>';
 // }else{
  //  echo '<div class="alert alert-danger">Verifique que los datos esten completos.<br>Verifique que la fecha sea mayor o igual a la de prestamo.<br> Verifique que el libro este disponible</div>';
  //}
    
  }else{
    echo '<div class="alert alert-danger">Verifique que los datos esten completos.<br>Verifique que la fecha sea mayor o igual a la de prestamo.<br> Verifique que el libro este disponible</div>';
  }
}
$prestamoArreglo = Prestamos::PrestamosSinDevolucion();
?>
<form class="form-horizontal" method="post">
  <fieldset>
    <div class="form-group">
      <label for="prestamo" class="col-lg-2 control-label">Prestamo</label>
      <div class="col-lg-6">
        <select name="prestamo" id="prestamo" class="form-control chosen">
          <?php while($fila = mysql_fetch_array($prestamoArreglo)){
            $UsuarioArreglo = new Usuario($fila['CodUsuario']);
            $LibroArreglo = new CopiaLibro($fila['CodCopia']);
			      $alohaa = new Libro($LibroArreglo->getCodLibro()); 

          ?>
          <option value="<?=$fila['CodPrestamo']?>">
          <?php 
          echo $UsuarioArreglo->getNombre().' '.$UsuarioArreglo->getApellido().' - '.$alohaa->getTitulo().' - '.$fila['Fecha'];
          ?>
          </option>
          <?php }?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <button type="reset" class="btn btn-default" >Cancelar</button>
        <button type="submit" class="btn btn-primary">Registrar</button>
      </div>
    </div>
  </fieldset>
</form>
</div>
</div>
</section>