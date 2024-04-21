<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$credentials = leerArchivo("bbdd.txt");

$conexion = mysqli_connect($credentials[0], $credentials[1], $credentials[2], $credentials[3]);

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Socios</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
</body>

</html>