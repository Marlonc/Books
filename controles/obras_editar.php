<section class="content-header">
    <h1>Obras</h1>
</section>
<section class="content">

<?php 
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once('clases/Libro.php');
require_once('clases/Autor.php');
require_once('clases/Editorial.php');
if($_POST)
{
  $titulo = $_POST['titulo'];
  $editorial = $_POST['editorial'];
  $fecha= $_POST['fecha'];
  $estado= $_POST['estado'];
  $errors =array();
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
    $libro = new Libro($_GET['id']);
    $libro->setTitulo($titulo);
    $libro->setAutores($autores);
    $libro->setEditorial($editorial);

    $libro->setEstado($estado);
    $libro->setFechaRegistro($fecha);
    
    if (isset($imagen) && $imagen['name'] != null) {
      $aleatorio = time();
      $imgUploader = new fileManager;
      $nombreImagen = $aleatorio.'-'.$imagen['name'];
      $imgUploader->setFileName($nombreImagen);
      $imgUploader->upload($imagen);
      $libro->setImagen($nombreImagen);
    }

    $resultado = $libro->modificaLibro();
    if($resultado > 0){
      $data = new Libro($_GET['id']);
       ?>
<div class="alert alert-success">Obra modificada exitosamente</div>
        <?php
    }
  }
}
  $libro = new Libro($_GET['id']);
?>
<div class="box box-danger">
  <div class="box-body">
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
      <fieldset>
        <legend>Modificar obras</legend>
        <div class="form-group">
          <label for="titulo" class="col-lg-2 control-label">Titulo</label>
          <div class="col-lg-10">
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?=$libro->getTitulo()?>" placeholder="Titulo">
          </div>
        </div>
        <div class="form-group">
          <label for="autor" class="col-lg-2 control-label">Autor</label>
          <div class="col-lg-10">
            <select multiple="multiple" name="autores[]" id="listaAutores" class="form-control chosen">
              <?php 
                $todosautores = Autores::Todo();
                $autores = Autores::where($_GET['id']);
                while ($a = mysql_fetch_array($todosautores)) {
                  $autor = new Autor($a['CodAutor']);
                  $text = '<option value="'.$autor->getCodAutor().'" ';
                  if(Autores::existe_en($_GET['id'],$a['CodAutor'])){
                    $text = $text.' selected="selected"';
                  }

                  echo $text.'>'.$autor->getNombre().'</option>';
                }
                
              ?>
            </select>
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#agregaautor">Añadir</a>
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
        <div class="form-group">
          <label for="editorial" class="col-lg-2 control-label">Editorial</label>
          <div class="col-lg-10">
            <select name="editorial" id="listaEditoriales" class="form-control chosen">
              <?php 
                $editoriales = Editoriales::Todo();
                while($fila = mysql_fetch_array($editoriales)){
                  $text = '<option value="'.$fila['CodEditorial'].'"';
                  if($fila['CodEditorial'] == $libro->getEditorial())
                    $text = $text.' selected="selected"';
                  echo $text.'>'.$fila['Nombre'].'</option>';
                  }
              ?>
            </select>
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#agregaeditorial">Añadir</a>
          </div>
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
        <div class="form-group">
          <label for="fecha" class="col-lg-2 control-label">Fecha</label>
          <div class="col-lg-10">
            <input type="text" class="form-control" id="fecha" name="fecha" value="<?=$libro->getFechaRegistro()?>" placeholder="Fecha">
          </div>
        </div>
        <div class="form-group">
          <label for="imagen" class="col-lg-2 control-label">Imagen</label>
          <div class="col-lg-10">
            <input type="file" class="form-control" id="imagen" name="imagen">
            <img src="<?=$libro->getImagen()?>" width="60px">
          </div>
        </div>
        <div class="form-group">
          <label for="imagen" class="col-lg-2 control-label">Estado</label>
          <div class="col-lg-10">
            <label>
                <input type="radio" class="flat-red" name="estado" <?=($libro->getEstado()==1)?'checked':''?> value="1"/>
                Activo
            </label>
            <label>
                <input type="radio" class="flat-red" name="estado" <?=($libro->getEstado()==1)?'':'checked'?>  value="0"/>
                Inactivo
            </label>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-10 col-lg-offset-2">
            <button type="reset" class="btn btn-default" >Cancelar</button>
            <button type="submit" class="btn btn-primary">Modificar</button>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>
</section>