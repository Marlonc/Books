<section class="content-header">
    <h1>Obras</h1>
</section>
<section class="content">
<?php 
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}

require_once("clases/Libro.php");
require_once("clases/Autor.php");
require_once("clases/Editorial.php");
if($_POST)
{
  $errors = array();
  $titulo = $_POST['titulo'];
  $editorial = $_POST['editorial'];
  $fecha = $_POST['fecha'];
  if(!(isset($_POST['autores']))){
    $errors['autores'][] = "Ingrese autores";
  }else{
    $autores = $_POST['autores'];
  }

  $imagen = $_FILES['imagen'];
  
  if(!(isset($titulo) && $titulo != ""))
    $errors['titulo'][] = "Ingrese titulo";
  if(!(isset($editorial) && $editorial != ""))
    $errors['editorial'][] = "Ingrese editorial";
  if(!(isset($fecha) && $fecha != ""))
    $errors['fecha'][] = "Ingrese fecha";
  if (isset($errors) && count($errors)==0) {
      $libro = Libros::crealibro($titulo,$editorial,1,$fecha,$autores,$imagen);
      if($libro > 0){
        $data = new Libro($libro);
?>

<div class="alert alert-success">Obra agregada exitosamente</div>
<div class="panel panel-default">
  <div class="panel-heading"><?=$data->getTitulo()?></div>
  <div class="panel-body">
    <dl>
      <dt>Editorial</dt>
      <dd><?php
      $e = new Editorial($data->getEditorial());
      echo $e->getNombre();
      ?></dd>
    </dl>
    <dl>
      <dt>Fecha</dt>
      <dd><?=$data->getFechaRegistro()?></dd>
    </dl>
    <dl>
      <dt>Autor(es):</dt>
      <dd>
        <?php
        foreach ($autores as $autor) {
          $a = new Autor($autor);
          echo '<span class="label label-success">'.$a->getNombre().'</span> ';
        }
        ?>
      </dd>
      <dl>
      <dt>Imagen</dt>
      <dd>
        <img src="<?=$data->getImagen()?>" width="60px">
      </dd>
    </dl>
  </div>
</div>
<?php    
    }
  }
}

?>
<div class="box box-danger">
  <div class="box-header"><h3 class="box-title">Agregar obras</h3></div>
  <div class="box-body">
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
      <fieldset>
        <div class="form-group form-group-sm">
          <label for="titulo" class="col-lg-2 control-label">Titulo</label>
          <div class="col-lg-10">
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Titulo"  value="<?=(isset($titulo))? $titulo:''?>">
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['titulo'])): ?>
          <?php foreach ($errors['titulo'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group form-group-sm">
          <label for="autor" class="col-lg-2 control-label">Autor</label>
          <div class="col-lg-10">
            <select multiple="multiple" name="autores[]" id="listaAutores" class="form-control chosen" placeholder="Seleccione autores">
              <?php 
              $autores = Autores::Todo();
              while($fila = mysql_fetch_array($autores)){
                echo '<option value="'.$fila['CodAutor'].'">'.$fila['Nombre'].'</option>';
                }
              ?>
            </select>
              <a class="btn btn-success btn-xs" data-toggle="modal" data-target="#agregaautor">Añadir</a>
          </div>
          <div class="modal fade" id="agregaautor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title" id="myModalLabel">Agregar autor</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group form-group-sm">
                    <label>Nombre de Autor</label>
                      <input type="text" class="form-control" placeholder="Ingrese nombre de autor" name="autorAgrega" id="autorAgrega"/>
                  </div>
                  <div id="estado_autor"></div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary"  id="botonAgregarAutor">Agregar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['autores'])): ?>
          <?php foreach ($errors['autores'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group form-group-sm">
          <label for="editorial" class="col-lg-2 control-label">Editorial</label>
          <div class="col-lg-10">
             <select name="editorial" id="listaEditoriales" class="form-control chosen">
                <?php 
              $editoriales = Editoriales::Todo();
              while($fila = mysql_fetch_array($editoriales)){
                echo '<option value="'.$fila['CodEditorial'].'">'.$fila['Nombre'].'</option>';
                }
            ?>
              </select>
              <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#agregaeditorial">Añadir</a>
              <div class="modal fade" id="agregaeditorial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                      <h4 class="modal-title" id="myModalLabel">Agregar Editorial</h4>
                    </div>
                    <div class="modal-body">
                      <div class="form-group form-group-sm">
                        <label>Nombre de la Editorial</label>
                          <input type="text" class="form-control" placeholder="Ingrese nombre de editorial" name="editorialAgrega" id="editorialAgrega"/>
                      </div>
                      <div id="estado_editorial"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary"  id="botonAgregareditorial">Agregar</button>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['editorial'])): ?>
          <?php foreach ($errors['editorial'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group form-group-sm">
          <label for="fecha" class="col-lg-2 control-label">Fecha</label>
          <div class="col-lg-10">
            <input type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha"  value="<?=(isset($fecha))? $fecha:''?>">
          </div>
        </div>
        <?php if (isset($errors) && isset($errors['fecha'])): ?>
          <?php foreach ($errors['fecha'] as $data): ?>
            <div class="alert alert-danger"><?=$data?></div>
          <?php endforeach ?>
        <?php endif ?>
        <div class="form-group form-group-sm">
          <label for="imagen" class="col-lg-2 control-label">Imagen</label>
          <div class="col-lg-10">
            <input type="file" class="form-control" id="imagen" name="imagen">
          </div>
        </div>
        <div class="form-group form-group-sm">
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
