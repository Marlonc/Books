<section class="content-header">
    <h1>Usuarios</h1>
</section>
<section class="content">
<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once("clases/usuario.php");
$usuario = new Usuario($_GET['id']);
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
  $estado = $_POST['estado'];
  $errors = array();

  if(isset($_POST['dni']) && strlen($_POST['dni']) != 10){
    $errors['dni'][] = 'Codigo debe tener 10 digitos y ser numérico';
  }
  if($usuario->getDNI() != $dni)
    if(isset($_POST['dni']) && strlen($_POST['dni']) != "" && Usuarios::busca($_POST['dni']))
      $errors['dni'][] = '<b>Ya existe</b> un usuario con ese dni';
  if(!(isset($_POST['nombres']) && strlen($_POST['nombres']) > 0)){
    $errors['nombres'][] = 'Ingrese nombres.';
  }
  if(!(isset($_POST['apellidos']) && strlen($_POST['apellidos']) > 0)){
    $errors['apellidos'][] = 'Ingrese apellidos.';
  }
  if(!(isset($_POST['tipo']) && is_numeric($_POST['tipo']))){
    $errors['tipo'][] = 'Error en la selección de tipo.';
  }
  if (isset($_POST) && count($errors)==0) {
    $usuario = new Usuario($_GET['id']);
    $usuario->setNombre($nombres);
    $usuario->setApellidos($apellidos);
    $usuario->setEstado($estado);
    $usuario->setTipo($tipo);
    $usuario->setDNI($dni);
    if(strlen($clave)>0)
      $usuario->setClave($clave);
    $resultado = $usuario->modificaUsuario();
    if($resultado == 1)
      echo '<div class="alert alert-success">Usuario modificado exitosamente</div>';
    else
      echo '<div class="alert alert-danger">Ocurrio un error</div>';
  }
}


?>
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Editar usuarios</h3></div>
  <div class="box-body">
    <form class="form-horizontal" method="post">
      <fieldset>
        <div class="form-group">
          <label for="dni" class="col-lg-2 control-label">Codigo</label>
          <div class="col-lg-4">
            <input type="text" class="form-control" id="dni" name="dni" value="<?=$usuario->getDNI()?>" placeholder="Codigo">
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
            <input type="text" class="form-control" id="nombres" name="nombres" value="<?=$usuario->getNombre()?>" placeholder="Nombres">
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
            <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?=$usuario->getApellido()?>" placeholder="Apellidos">
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
            <input type="password" class="form-control" id="clave" name="clave" placeholder="Clave">
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
               $activo  = ($i == $usuario->getTipo())? 'select selected' :" "; 
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
              <label for="imagen" class="col-lg-2 control-label">Estado</label>
              <div class="col-lg-4">
                <label>
                    <input type="radio" class="flat-red" name="estado" <?=($usuario->getEstado()==1)?'checked':''?> value="1"/>
                    Activo
                </label>
                <label>
                    <input type="radio" class="flat-red" name="estado" <?=($usuario->getEstado()==1)?'':'checked'?>  value="0"/>
                    Inactivo
                </label>
              </div>
            </div>
        <div class="form-group">
          <div class="col-lg-4 col-lg-offset-2">
            <button type="reset" class="btn btn-default" >Cancelar</button>
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>
</section>