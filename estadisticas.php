<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();



$numSocios=mysqli_query($conexion,"SELECT COUNT(*) AS num FROM socio");
$numSocios = mysqli_fetch_assoc($numSocios)["num"];
$numSociosAlta=mysqli_query($conexion,"SELECT COUNT(*) AS num FROM socio WHERE baja=0");
$numSociosAlta = mysqli_fetch_assoc($numSociosAlta)["num"];

$numFamilias=mysqli_query($conexion,"SELECT COUNT(*) AS num FROM familia");
$numFamilias = mysqli_fetch_assoc($numFamilias)["num"];
$numFamiliasAlta=mysqli_query($conexion,"SELECT COUNT(*) AS num FROM familia WHERE baja=0");
$numFamiliasAlta = mysqli_fetch_assoc($numFamiliasAlta)["num"];

$familias=mysqli_query($conexion,"SELECT id FROM familia");
$numFamilias = mysqli_fetch_assoc($numFamilias)["num"];

$cuotaTotal=0;
$cuotaRestante=0;



?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Estadísticas</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Estadísticas</h1>
    </div>
    <div class="contenedor">
        <div class="campo">
            <p>Número de socios total:</p>
            <p><?php echo $numSocios ?></p>
        </div>
        <div class="campo">
            <p>Número de socios de alta:</p>
            <p><?php echo $numSociosAlta ?></p>
    </div>


</body>

</html>