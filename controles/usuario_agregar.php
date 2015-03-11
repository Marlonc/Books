<section class="content-header">
    <h1>Usuarios</h1>
</section>
<section class="content">
<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once('clases/Usuario.php');
$rangos =  array(
    1 => 'Administrador',
    2 => 'Personal',
    3 => 'Cliente' );
if($_POST)
{
  $dni = $_POST['dni'];
  $nombres = $_POST['nombres'];
  $apellidos = $_POST['apellidos'];
  $clave = $_POST['clave'];
  $tipo = $_POST['tipo'];
  $errors = array();

  if(isset($_POST['dni']) && strlen($_POST['dni']) != 8){
    $errors['dni'][] = 'DNI debe tener 8 digitos y ser numérico';
  }
  if(isset($_POST['dni']) && strlen($_POST['dni']) != "" && Usuarios::busca($_POST['dni']))
  {
    $errors['dni'][] = '<b>Ya existe</b> un usuario con ese dni';
  }
  if(!(isset($_POST['nombres']) && strlen($_POST['nombres']) > 0)){
    $errors['nombres'][] = 'Ingrese nombres.';
  }
  if(!(isset($_POST['apellidos']) && strlen($_POST['apellidos']) > 0)){
    $errors['apellidos'][] = 'Ingrese apellidos.';
  }
  if(!(isset($_POST['clave']) && strlen($_POST['clave']) > 4)){
    $errors['clave'][] = 'la Clave debe tener mas de 4 digitos.';
  }
  if(!(isset($_POST['tipo']) && is_numeric($_POST['tipo']))){
    $errors['tipo'][] = 'Error en la sección de tipo.';
  }
  if (isset($_POST) && count($errors)==0) {
      Usuarios::crea($dni,$nombres,$apellidos,1,$clave,$tipo);
      echo '<div class="alert alert-success">Usuario agregado exitosamente</div>';
  }
}
  
?>
<div class="box box-danger">
  <div class="box-header">
    <h3 class="box-title">Agregar usuario</h3>
  </div>  
  <div class="box-body">
    <form class="form-horizontal" method="post">
      <fieldset>
        <div class="form-group">
          <label for="dni" class="col-lg-2 control-label">Codigo</label>
          <div class="col-lg-4">
            <input type="text" class="form-control" id="dni" name="dni" placeholder="Codigo" value="<?=($_POST)? $dni :''?>">
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['dni'])): ?>
          <?php foreach ($errors['dni'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group">
          <label for="nombres" class="col-lg-2 control-label">Nombres</label>
          <div class="col-lg-4">
            <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombres" value="<?=($_POST)? $nombres :''?>">
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['nombres'])): ?>
          <?php foreach ($errors['nombres'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group">
          <label for="apellidos" class="col-lg-2 control-label">Apellidos</label>
          <div class="col-lg-4">
            <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Apellidos" value="<?=($_POST)? $apellidos :''?>">
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['apellidos'])): ?>
          <?php foreach ($errors['apellidos'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group">
          <label for="clave" class="col-lg-2 control-label">Clave</label>
          <div class="col-lg-4">
            <input type="password" class="form-control" id="clave" name="clave" placeholder="Clave" value="<?=($_POST)? $clave :''?>">
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['clave'])): ?>
          <?php foreach ($errors['clave'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group">
          <label for="clave" class="col-lg-2 control-label">Tipo</label>
          <div class="col-lg-4">
            <select name="tipo" class="form-control">
              <?php 
              for ($i=1; $i <= count($rangos); $i++) {
               $activo  = ($_POST && $i == $tipo)? 'select selected' :" "; 
                echo '<option value="'.$i.'" '.$activo.'>'.$rangos[$i].'</option>';          
              }
              ?>
            </select>
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['tipo'])): ?>
          <?php foreach ($errors['tipo'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group">
          <div class="col-lg-10 col-lg-offset-2">
            <button type="reset" class="btn btn-default" >Cancelar</button>
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>
</section>