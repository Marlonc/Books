<section class="content-header">
    <h1>Reportes</h1>
</section>
<section class="content">
<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
require_once("clases/Libro.php");
?>
<div class="row"><?php
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
                <a class="btn btn-danger" href="index.php?menu=reporte_listar_por_ano&year=<?=$_GET['year']?>&year=<?=$year-1?>">&lt;</a>
                <?=$year;?>
                <a class="btn btn-danger" href="index.php?menu=reporte_listar_por_ano&year=<?=$_GET['year']?>&year=<?=$year+1?>">&gt;</a>
                )
          </h3>
      </div>
      <div class="box-body chart-responsive">
            <div class="chart" id="line-chart" style="height: 300px;"></div>
        </div><!-- /.box-body -->
    </div>
  </div>
</div>
<?php
if (isset($_GET['year']) && is_numeric($_GET['year'])) {
  $datos = libros::all($_GET['year']);
}else{
  $datos = libros::all(date('Y'));
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
</section>