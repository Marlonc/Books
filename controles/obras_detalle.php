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
require_once("clases/Autor.php");
require_once("clases/Editorial.php");
if(isset($_GET['obra']) && is_numeric($_GET['obra'])){
  $data = new Libro($_GET['obra']);
  if($data->getError()==0){
?>
<section class="content-header">
    <h1>
        <?=$data->getTitulo()?>
        <a href="index.php?menu=obras_editar&id=<?=$data->getCodLibro()?>" class="btn btn-warning btn-xs">Modificar</a>
    </h1>
</section>
<section class="content">
<div class="row">
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-6">
            <dl>
              <dt>Editorial</dt>
              <dd><?php
              $e = new Editorial($data->getEditorial());
              ?>
              <a href="index.php?menu=obras_buscar&editorial=<?=$data->getEditorial()?>"><?=$e->getNombre()?></a>
              </dd>
              <dt>Fecha</dt>
              <dd><?=$data->getFechaRegistro()?></dd>
              <dt>Autor(es):</dt>
              <dd>
                <?php
                while ($autor= mysql_fetch_array($data->getAutores())) {
                  $a = new Autor($autor['CodAutor']);
                  echo '<span class="label label-success">'.$a->getNombre().'</span> ';
                }
                ?>
              </dd>
            </dl>
          </div>
          <div class="col-sm-6">
            <img src="<?=$data->getImagen()?>">
          </div>
        </div>
        
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <dl>
      <dt>Cantidad de Copias:</dt>
      <dd><?=$data->cantidadCopias()?></dd>
      <?php
        if(isset($_GET['year']) && is_numeric($_GET['year'])){
          $year = $_GET['year'];
        }else{
          $year = date('Y');
        }
        ?>
      <div class="box box-danger">
          <div class="box-header">
              <h3 class="box-title">
                Prestamos al año(
                    <a class="btn btn-danger" href="index.php?menu=obras_detalle&obra=<?=$_GET['obra']?>&year=<?=$year-1?>">&lt;</a>
                    <?=$year;?>
                    <a class="btn btn-danger" href="index.php?menu=obras_detalle&obra=<?=$_GET['obra']?>&year=<?=$year+1?>">&gt;</a>
                    )
              </h3>
          </div>
          <div class="box-body chart-responsive">
              <div class="chart" id="line-chart" style="height: 300px;"></div>
          </div><!-- /.box-body -->
      </div>

    </dl>
  </div>
</div>
<?php
  }else{
    echo '<div class="alert alert-danger">Obra solicitada no se encontró</div>';
  }
}else{
  echo '<div class="alert alert-danger">Obra solicitada no se encontró</div>';
}
?>
</section>
<?php
if (isset($_GET['year']) && is_numeric($_GET['year'])) {
  $datos = $data->estaditicaAnual($_GET['year']);
}else{
  $datos = $data->estaditicaAnual(date('Y'));
}
?>

<script type="text/javascript">
window.onload = function(){
  var datos = <?php echo json_encode($datos)?>;
  console.log(datos);
  var line = new Morris.Line({
      element: 'line-chart',
      resize: true,
      data: datos,
      xkey: 'year',
      ykeys: ['value'],
      labels: ["value"],
      lineColors: ['#3c8dbc'],
      hideHover: 'auto',
      parseTime:false
  });
}
</script>
