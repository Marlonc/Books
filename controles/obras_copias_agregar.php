<section class="content-header">
    <h1>Obras</h1>
</section>
<section class="content">
<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
  require_once("clases/Libro.php");
  require_once("clases/CopiaLibro.php");
  if ($_POST) {
    $libro = $_POST['libro'];
    $total = intval($_POST['total']);
    $errors = array();
    if(!(isset($libro) && is_numeric($libro)))
      $errors['libro'][] = "Error en codigo de obra";
    if(!(isset($total) && is_numeric($total) && is_integer($total)))
      $errors['total'][] = "<b>Cantidad</b> ser numérica y entera";
    if(!(isset($total) && $total > 0))
      $errors['total'][] = "<b>Cantidad</b> debe ser mayor a 0";
    if(isset($errors) && count($errors)==0){
      CopiaLibros::crea($libro,$total);
      $libro = new Libro($libro);
      echo '<div class="alert alert-success">Fueron agregados <b>'.$total.'</b> al libro <b>'.$libro->getTitulo().'</b></div>';
    }
  }
?>
<div class="box box-danger">
  <div class="box-header"><h3 class="box-title">Agregar copias de obras</h3></div>
</div>
  <div class="box-body">
    <form class="form-horizontal" method="post">
        <div class="form-group">
          <label for="libro" class="col-lg-2 control-label">Obras</label>
          <div class="col-lg-10">
            <select name="libro" id="libro" class="form-control chosen">
              <?php
              $query = Libros::LibrosActivos();
              while ($fila = mysql_fetch_array($query)) {
                echo '<option value="'.$fila['CodLibro'].'">('.$fila['CodLibro'].') '.$fila['Titulo'].'</option>';
              }
              ?>
            </select>
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['libro'])): ?>
          <?php foreach ($errors['libro'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <br><br>
        <div class="form-group">
          <label for="total" class="col-lg-2 control-label">Cantidad</label>
          <div class="col-lg-3">
            <input type="text" class="form-control" id="total" name="total" placeholder="Cantidad de copias">
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['total'])): ?>
          <?php foreach ($errors['total'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <br><br>
        <div class="form-group form-group-sm">
        <div class="col-lg-10 col-lg-offset-2">
          <button type="reset" class="btn btn-default" >Cancelar</button>
          <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
        </div>
    </form>
  </div>
</section>