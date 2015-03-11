<section class="content-header">
    <h1>Pagina Principal</h1>
</section>

<!-- Main content -->
<section class="content">
<div class="box box-danger">
    <div class="box-header"><h3 class="box-title">Reportes generales</h3></div>
</div>
    <?php
    require_once("clases/Prestamo.php");
    ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-info">
                <div class="panel-heading">Ultimos 5 Prestamos <div class="pull-right"><a href="index.php?menu=prestamo_listar" class="btn btn-info btn-xs">Ver Todos</a></div></div>
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
                        $topPrestamos = Prestamos::TopFecha(5,date('Y-m-d'));
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
                            echo 'Hoy no hay prestamos';
                        }

                    ?>  
                </tbody>
                </table>    
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-info">
                <div class="panel-heading">Libros con fecha de devolucion (<?=date('d/m/Y')?>)</div>
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
                        $topDevolucion = Prestamos::Top(5);
                        if ($topDevolucion) {
                            while ($fila = mysql_fetch_array($topDevolucion)) {
                                $pretamo = new Prestamo($fila['CodPrestamo']);
                                if($pretamo->calculaFechaDevolucion() == date('Y-m-d')){
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
                            }}  
                        }else{
                            echo 'Hoy no hay prestamos';
                        }

                    ?>  
                </tbody>
                </table>    
            </div>
        </div>
    </div>
</section><!-- /.content -->