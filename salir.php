<?php
include("includes/config.php");
session_unset($_SESSION['usuario']);
session_destroy();
header("Location: index.php");
?>