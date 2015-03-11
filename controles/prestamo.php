<?php
require_once("clases/Libro.php");
require_once("clases/Prestamo.php");
require_once("clases/Usuario.php");
if($_POST)
{
  $usuario = $_POST['usuario'];
  $copia = $_POST['copia'];
  $fecha = $_POST['fecha'];

  if (isset($usuario) && isset($copia) && isset($fecha) &&
    $usuario != '' && $copia != '' && $fecha != '') {
    $user = new Usuario($usuario);
    if($user->LimitePrestamo()==true){
      $prestado= Prestamos::creaprestamo($usuario,$copia,$fecha,0);
      echo '<div class="alert alert-success">Pestamo agregado exitosamente</div>';
      echo $user->LimitePrestamo();
    }else{
      echo '<div class="alert alert-danger">Este usuario ya realizo 3 prestamos, debe devolver al menos 1 para poder registrar mas prestamos</div>';
      echo $user->LimitePrestamo();
    }
    
  }else{
    echo '<div class="alert alert-danger">Verifique que los datos esten completos.<br>Verifique que la fecha sea mayor o igual a la actual.<br> Verifique que el libro este disponible</div>';
  }
}

//seleccionamos los libros activos
$libros = Libros::LibrosActivos();
//seleccionamos los usuarios activos  
$usuarios = Usuarios::Todos();
?>
<form class="form-horizontal" method="post">
  <fieldset>
    <legend>Registrar prestamo</legend>
    <div class="form-group">
      <label for="libros" class="col-lg-2 control-label">Obra</label>
      <div class="col-lg-10">
        <select name="libros" id="libros" class="form-control chosen">
          <?php while($fila = mysql_fetch_array($libros)){?>
          <option value="<?=$fila['CodLibro']?>"><?=$fila['Titulo']?></option>
          <?php }?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="copia" class="col-lg-2 control-label">Copias de libro</label>
       <div class="col-lg-10">
          <select name="copia" id="copia" class="form-control">
          </select>
        </div>
    </div>
    <div class="form-group">
      <label for="usuario" class="col-lg-2 control-label">Usuario</label>
       <div class="col-lg-10">
          <select name="usuario" id="usuario" class="form-control chosen">
            <?php while($fila = mysql_fetch_array($usuarios)){?>
            <option value="<?=$fila['CodUsuario']?>" <?=($fila['Estado']==0)? 'disabled="disabled"':''?>><?=$fila['Nombres']?> <?=$fila['Apellido']?><?=($fila['Estado']==0)? '(Inactivo)':''?></option>
            <?php }?>
          </select>
        </div>
    </div>
    <div class="form-group">
      <label for="fecha" class="col-lg-2 control-label">Fecha</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" id="fecha" data-date-format="DD/MM/YYYY" name="fecha" placeholder="Fecha">
      </div>
    </div>
    <div class="form-group">
      <div class="pull-right">
        <button type="reset" class="btn btn-default" >Cancel</button>
        <button type="submit" class="btn btn-primary">Registrar</button>
      </div>
    </div>
  </fieldset>
</form>