<?php 
require('includes/config.php');
require('clases/Usuario.php');
if ($_POST) {
  if(isset($_SESSION['usuario'])){
    header("Location: index.php");
  }
  $dni = $_POST['dni'];
  $nombres = $_POST['nombres'];
  $apellidos = $_POST['apellidos'];
  $clave = $_POST['clave'];

  $errors = array();
  if(isset($_POST['dni']) && strlen($_POST['dni']) != 10){
      $errors[] = 'El Codigo debe tener 10 digitos y ser numérico';
    }
    if(isset($_POST['dni']) && Usuarios::busca($_POST['dni']))
    {
      $errors[] = '<b>Ya existe</b> un usuario con ese Codigo';
    }
    if(isset($_POST['nombres']) && strlen($_POST['nombres']) <= 0){
      $errors[] = 'Ingrese Nombre.';
    }
    if(isset($_POST['apellidos']) && strlen($_POST['apellidos']) <= 0){
      $errors[] = 'Ingrese Apellido.';
    }
    if(isset($_POST['clave']) && strlen($_POST['clave']) <4){
      $errors[] = 'la Clave debe tener mas de 4 digitos.';
    }
    if (isset($_POST) && count($errors)==0) {
      Usuarios::crea($_POST['dni'],$_POST['nombres'],$_POST['apellidos'],1,$_POST['clave'],3);
      if(Usuarios::valida($_POST['dni'],$_POST['clave']))
      {
        header("Location: index.php");
      }
    }
}  
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse - Cuenta Books</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-title" content="Jorge Botello-Books">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="google" value="notranslate"/>
    <meta name="author" content="Jorge Botello, @RootKzero"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Books">
    <meta property="og:site_name" content="Books">
    <meta property="og:url" content="http://mrjorgebotello.pe.hu/">
    <!--Acerca, CSS, Favicon-->
    <link rel="acerca" type="application/json" href="config/about.json">
    <link type="text/css" rel="stylesheet" href="assets/css/style.css"  media="screen,projection"/>

    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon" />
</head>
<body>
    <div class="form--books">
        <div class="logo">
            <img src="assets/images/logo.png" alt="Books"/>
        </div>
            <h1>Registrarse</h1>
        <form action="" method="post">
            <div class="input--base">
            <input class="input-field" id="dni" name="dni" placeholder="Codigo" type="text" size="18" required="required" value="<?=($_POST)? $dni :''?>"/>
            </div>
            <div class="input--base">
            <input class="input-field" id="nombres" name="nombres" placeholder="Nombres" type="text" size="18" required="required"  value="<?=($_POST)? $nombres :''?>"/>
            </div>
            <div class="input--base">
            <input class="input-field" id="apellidos" name="apellidos" placeholder="Apellidos" type="text" size="18" required="required" value="<?=($_POST)? $apellidos :''?>"/>
            </div>
            <div class="input--base">
            <input class="input-field" id="clave" name="clave" placeholder="Clave" type="password" size="18" required="required"  value="<?=($_POST)? $clave :''?>"/>
            </div>
            <input class="registrarme" type="submit" value="Registrarme">
            <br><center>
                <?php if(isset($errors)):?>
                    <div class="">  
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><?=$error?></li>
                    <?php endforeach ?>
                </ul>
                    </div>
                <?php endif?>
            </center>
        </form>
    </div>
    <footer class="footer">
        <div class="books--f">Books</div>
        <div class="copyright">
            <div>Copyright © 2015 <a href="http://mrjorgebotello.pe.hu/" target="_blank">Jorge Botello</a></div>
        </div>
    </footer>
    <!-- Google Analytics -->
    <script>
    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
    function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
    e=o.createElement(i);r=o.getElementsByTagName(i)[0];
    e.src='//www.google-analytics.com/analytics.js';
    r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
    ga('create',$gAnalytics);ga('send','pageview');
    </script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <!--script-->
    <script src="assets/js/prefixfree.min.js"></script>
</body>
</html>