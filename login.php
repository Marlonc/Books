<?php 
require('includes/config.php');
require('clases/Usuario.php');
//Valida si el usuario esta conectado
  if(isset($_POST['dni']))
    if(Usuarios::valida($_POST['dni'],$_POST['clave']))
    {
      header("Location: index.php");
    }else{
      $error = true;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Books</title>
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
            <h1>Iniciar sesión</h1>
        <form action="" method="post">
            <div class="input--base">
            <input class="input-field" name="dni" placeholder="Ingrese su Codigo" type="text" size="18" required="required"/>
            </div>
            <div class="input--base">
            <input class="input-field" name="clave" placeholder="Ingrese su Clave" type="password" size="18" required="required"/>
            </div>
            <input class="ingresar" type="submit" value="Ingresar">
            <br><center>
                <?php if(isset($error) && $error == true):?>
                <div class=""><strong>Error:</strong> Verifique los datos</div>
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