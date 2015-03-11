<?php 
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
if($_POST)
{
  $usuarios = $_POST['usuarios'];
  $libros = $_POST['libros'];
  $fecha = $_POST['fecha'];
  if (isset($usuarios) && isset($libros) && isset($fecha) &&
    $usuarios != '' && $libros != '' && $fecha != '') {
    $prestamo = PrestamoDetalle($_GET['id']);
    if(LibroDisponible($libros) || $libros == $prestamo['CodLibro']){
      $q = mysql_query("UPDATE prestamo SET CodLibro=$libros, CodUsuario=$usuarios, Fecha='".date("Y-m-d",strtotime($fecha))."' WHERE CodPrestamo=".$_GET['id']."");
      if($libros != $prestamo['CodLibro']){
        mysql_query("UPDATE libro SET Prestamo=0 WHERE CodLibro=".$prestamo['CodLibro']."");
        mysql_query("UPDATE libro SET Prestamo=1 WHERE CodLibro=$libros");
      }
      if(!$q)
        die('Error en insertar');
      echo '<div class="alert alert-success">Prestamo modificado exitosamente</div>';
    }
  }else{
    echo '<div class="alert alert-danger">Verifique que los datos esten completos.<br> Verifique que el libro este disponible</div>';
  }
}
$prestamo = PrestamoDetalle($_GET['id']);

$usuario = UsuarioDetalle($prestamo['CodUsuario']);
$libro = LibroDetalle($prestamo['CodLibro']);
//seleccionamos los libros activos
$librosArreglo = LibrosActivos();
//seleccionamos los usuarios activos  
$usuariosArreglo = UsuariosActivos();

?>
<form class="form-horizontal" method="post">
  <fieldset>
    <legend>Modificar prestamo</legend>
    <div class="form-group">
      <label for="libros" class="col-lg-2 control-label">Obra</label>
      <div class="col-lg-10">
        <select name="libros" id="libros" class="form-control">
          <?php while($fila = mysql_fetch_array($librosArreglo)){?>
          <option value="<?=$fila['CodLibro']?>" <?php if($libro['CodLibro'] == $fila['CodLibro']){ echo 'selected="selected"'; } ?>><?=$fila['Titulo']?> - <?php if($fila['Prestamo']==1) echo 'En Prestamo';else echo 'Disponible';?></option>
          <?php }?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="usuarios" class="col-lg-2 control-label">Usuario</label>
       <div class="col-lg-10">
          <select name="usuarios" id="usuarios" class="form-control">
            <?php while($fila = mysql_fetch_array($usuariosArreglo)){?>
            <option value="<?=$fila['CodUsuario']?>" <?php if($usuario['CodUsuario'] == $fila['CodUsuario']){ echo 'selected="selected"'; } ?> ><?=$fila['Nombres']?> <?=$fila['Apellido']?></option>
            <?php }?>
          </select>
        </div>
    </div>
    <div class="form-group">
      <label for="fecha" class="col-lg-2 control-label">Fecha</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" id="fecha" data-date-format="DD/MM/YYYY" name="fecha" value=" <?=date("d/m/Y",strtotime($prestamo['Fecha'])) ?>" placeholder="Fecha">
      </div>
    </div>
    <div class="form-group">
      <div class="pull-right">
        <button type="reset" class="btn btn-default" >Cancel</button>
        <button type="submit" class="btn btn-primary">Modificar</button>
      </div>
    </div>
  </fieldset>
</form>