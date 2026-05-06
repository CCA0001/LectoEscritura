<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.html");
    exit();
}

include("../views/moduloLectura.php");
?>