<?php
require_once ('clases/Prestamo.php');
require_once ('clases/Usuario.php');
usuarioConectado();
$usuario = new Usuario($_SESSION['usuario']['CodUsuario']);
if ($usuario->getEstado() == 0) {
	echo '<div class="alert alert-danger">Su cuenta a sido inhabilitada <a href="index.php?menu=cliente_sanciones">Ver sanciones</a></div>';
}
?>
<section class="content-header">
    <h1>Principal</h1>
</section>
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Bienvenido <?=$usuario->getNombre()?></h3></div>
</div>
<div class="row">
	<div class="panel panel-info">
        <div class="panel-heading">Ultimos 5 Prestamos <div class="pull-right"><a href="index.php?menu=cliente_prestamos" class="btn btn-info btn-xs">Ver Todos</a></div></div>
        <table class="table">
        <thead>
            <tr>
              <th>#</th>
              <th>Usuario</th>
              <th>Libro</th>
              <th>Prestamo</th>
            </tr>
      </thead>
        <tbody>
            <?php
                $topPrestamos = Prestamos::TopUsuario(5,$_SESSION['usuario']['CodUsuario']);
                if ($topPrestamos) {
                    while ($fila = mysql_fetch_array($topPrestamos)) {
            ?>

            <tr>
                <td><?=$fila['CodPrestamo']?></td>
                <td>
                    <?php 
                        $arregloUsuario = new Usuario($fila['CodUsuario']);
                        echo $arregloUsuario->getNombre().' '.$arregloUsuario->getApellido();
                    ?>
                </td>
                <td>
                    <?php
                        $arregloLibro = new CopiaLibro($fila['CodCopia']);
                        $libro = new Libro($arregloLibro->getCodLibro());
                        echo '<a href="index.php?menu=obras_detalle&obra='.$libro->getCodLibro().'">'.$libro->getTitulo().'</a>';
                    ?>
                </td>
                <td>
                    <?=$fila['Fecha']?>
                </td>
            <?php
                    }   
                }else{
                    echo '<tr><td>Hoy no hay prestamos</td></tr>';
                }

            ?>  
        </tbody>
        </table>    
    </div>
</div>
</section>