

<section class="content-header">
    <h1>
        Sanciones
        <small>agregar sanción</small>
    </h1>
</section>
<section class="content">
<?php
if(!($_SESSION['usuario']['tipo'] == 1 || $_SESSION['usuario']['tipo'] == 2))
{
  echo '<script language="javascript">window.location.href = "error.php";</script>';
}
	require_once('clases/Libro.php');
	require_once('clases/Prestamo.php');
	require_once('clases/CopiaLibro.php');
	$libros = Libros::Todo();
	if (isset($_GET['year'])) {
		$year = $_GET['year'];
	}else{
		$year = date('Y');
	}
	
	while ($fila = mysql_fetch_array($libros)) {
		$libro = $fila['CodLibro'];
		$contador = 0;

		$copias = CopiaLibros::where('CodLibro','=',$libro);
		while ($fila2 = mysql_fetch_array($copias)) {
			$sql ="SELECT * FROM prestamo WHERE CodCopia =".$fila2['CodCopia']." AND year(Fecha) = $year ";
			$prestamos = mysql_query($sql);
			if (!is_bool($prestamos)) {
				$contador += mysql_num_rows($prestamos);
			}
			
		}
		$contadorLibro[] = (object)array('y' =>$contador,'a' => $fila['Titulo'],'b' => $fila['Titulo'] );
		$titulos[] = $fila['Titulo'];
	}

?>
<div class="row"><?php
        if(isset($_GET['year']) && is_numeric($_GET['year'])){
          $year = $_GET['year'];
        }else{
          $year = date('Y');
        }
        ?>
   <div class="box box-info">
      <div class="box-header">
          <h3 class="box-title">
            Prestamos al año(
                <a class="btn btn-info" href="index.php?menu=obras_mas_prestadas&year=<?=$_GET['year']?>&year=<?=$year-1?>">&lt;</a>
                <?=$year;?>
                <a class="btn btn-info" href="index.php?menu=obras_mas_prestadas&year=<?=$_GET['year']?>&year=<?=$year+1?>">&gt;</a>
                )
          </h3>
      </div>
      <div class="box-body chart-responsive">
            <div class="chart" id="bar-chart" style="height: 300px;"></div>
        </div><!-- /.box-body -->
    </div>
  </div>
</div>
<script type="text/javascript">
window.onload = function(){
  var datos = <?php echo json_encode($contadorLibro)?>;
  var titulos = <?php echo json_encode($titulos)?>;
  console.log(datos);
  var line = new Morris.Bar({
    element: 'bar-chart',
    resize: true,
    data: datos,
    barColors: ['#00a65a','#00a65a'],
    xkey: 'y',
    ykeys: ['a','b'],
    labels: titulos,
    hideHover: 'auto'
  });
}
</script>
</section>