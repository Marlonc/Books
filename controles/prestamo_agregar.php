<section class="content-header">
    <h1>Prestamos</h1>
</section>
<section class="content">
<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once("clases/Libro.php");
require_once("clases/Prestamo.php");
require_once("clases/Usuario.php");
if($_POST)
{
  $errors = array();
  $usuario = $_POST['usuario'];
  if(isset($_POST['copia']))
    $copia = $_POST['copia'];
  else
    $errors['copia'][] = 'Error en seleccion de copia'; 

  if(!(isset($usuario) && is_numeric($usuario)))
    $errors['usuario'][] = 'Error en seleccion de usuario';

  if (isset($errors) && count($errors)==0) {
    $user = new Usuario($usuario);
    if($user->LimitePrestamo()==true){
      $prestado= Prestamos::creaprestamo($usuario,$copia,date('d/m/Y'),0);
      echo '<div class="alert alert-success">Pestamo agregado exitosamente</div>';
      echo $user->LimitePrestamo();
    }else{
      echo '<div class="alert alert-danger">Este usuario ya realizo 3 prestamos, debe devolver al menos 1 para poder registrar mas prestamos</div>';
      echo $user->LimitePrestamo();
    }
    
  }
}

//seleccionamos los libros activos
$libros = Libros::LibrosActivos();
//seleccionamos los usuarios activos  
$usuarios = Usuarios::Todos();
?>
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Agregar prestamos</h3></div>
  <div class="box-body">
    <form class="form-horizontal" method="post">
      <fieldset>
        <div class="form-group">
          <label for="libros" class="col-lg-2 control-label">Obra</label>
          <div class="col-lg-6">
            <select name="libros" id="libros" class="form-control chosen">
              <?php while($fila = mysql_fetch_array($libros)){?>
              <option value="<?=$fila['CodLibro']?>"><?=$fila['Titulo']?></option>
              <?php }?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="copia" class="col-lg-2 control-label">Copias de libro</label>
           <div class="col-lg-6">
              <select name="copia" id="copia" class="form-control">
              </select>
            </div>
        </div>
        <?php if (isset($errors) && isset($errors['copia'])): ?>
          <?php foreach ($errors['copia'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group">
          <label for="usuario" class="col-lg-2 control-label">Usuario</label>
           <div class="col-lg-6">
              <select name="usuario" id="usuario" class="form-control chosen">
                <?php while($fila = mysql_fetch_array($usuarios)){?>
                <option value="<?=$fila['CodUsuario']?>" <?=($fila['Estado']==0)? 'disabled="disabled"':''?>><?=$fila['Nombres']?> <?=$fila['Apellido']?><?=($fila['Estado']==0)? '(Inactivo)':''?></option>
                <?php }?>
              </select>
            </div>
        </div>
        <?php if (isset($errors) && isset($errors['usuario'])): ?>
          <?php foreach ($errors['usuario'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group form-group-sm">
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