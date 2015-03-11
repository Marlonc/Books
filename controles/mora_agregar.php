<section class="content-header">
    <h1>Sanciones</h1>
</section>
<section class="content">
<?php 
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}

require_once("clases/Libro.php");
require_once("clases/Autor.php");
require_once("clases/Prestamo.php");
require_once("clases/Editorial.php");
if($_POST)
{
  $usuario = $_POST['usuario'];
  $razon = $_POST['razon'];
  $fecha = $_POST['fecha'];
  $dias = $_POST['dias'];
  $errors=array();
  if(!(isset($usuario) && is_numeric($usuario)))
    $errors['usuario'][] = "Error en usuario";
  if(isset($_POST['prestamo']))
    $prestamo = $_POST['prestamo'];
  else
    $errors['prestamo'][] = "Error en prestamo";
    
  if(!(isset($fecha) && strlen($fecha)>0))
    $errors['fecha'][] = "Ingrese fecha";
  if(!(isset($dias)))
    $errors['dias'][] = "Error en dias";

    if (isset($errors) && count($errors)==0) {
      $fechaConvertida = str_replace('/', '-', $fecha);
      $fecha = date("Y-m-d",strtotime($fechaConvertida));
      $fechaFin = strtotime( '+'.$dias.' day' , strtotime( $fecha )) ;  
      $fechaFin = date ( 'Y-m-j' , $fechaFin );
      $mora = Moras::crea($fecha,$fechaFin,$prestamo,$razon,1);
      echo '<div class="alert alert-success">Sancion agregada con exito</div>';
      
    }

}
$usuarios = Usuarios::Todos();
?>
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Agregar sanción</h3></div>
  <div class="box-body">
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
      <fieldset>
        <div class="form-group">
          <label for="usuario" class="col-lg-2 control-label">Usuario</label>
           <div class="col-lg-6">
              <select name="usuario" id="usuario" class="form-control chosen">
                <option>Seleccione</option>
                <?php while($fila = mysql_fetch_array($usuarios)){?>
                <option value="<?=$fila['CodUsuario']?>"><?=$fila['Nombres']?> <?=$fila['Apellido']?><?=($fila['Estado']==0)? '(Inactivo)':''?></option>
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
          <label for="prestamo" class="col-lg-2 control-label">Prestamos</label>
          <div class="col-lg-6">
            <select class="form-control" name="prestamo" id="prestamo">
              
            </select>
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['prestamo'])): ?>
          <?php foreach ($errors['prestamo'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group form-group-sm">
          <label for="fecha" class="col-lg-2 control-label">Fecha</label>
          <div class="col-lg-6">
            <input type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha">
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['fecha'])): ?>
          <?php foreach ($errors['fecha'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group form-group-sm">
          <label for="dias" class="col-lg-2 control-label">Dias</label>
          <div class="col-lg-1">
            <select class="form-control" name="dias">
              <?php
              for ($i=1; $i <= 31; $i++) { 
                echo '<option value="$i">'.$i.'</option>';
              }
              ?>
            </select>
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['dias'])): ?>
          <?php foreach ($errors['dias'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group">
          <label for="razon" class="col-lg-2 control-label">Razon</label>
          <div class="col-lg-6">
          <textarea name="razon" class="form-control" rows="4"></textarea>
          </div>
        </div>
        <div class="form-group form-group-sm">
          <div class="col-lg-6 col-lg-offset-2">
            <button type="reset" class="btn btn-default" >Cancelar</button>
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>
</section>