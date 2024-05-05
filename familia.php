<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

if (isset($_GET["id"])) {
    $familia = mysqli_query($conexion, "SELECT * FROM familia WHERE id=" . $_GET["id"]);

} else {
    header("location: .");
}

$row = $familia->fetch_assoc();
$cabeza = mysqli_query($conexion, "SELECT nombre,apellidos FROM socio WHERE id=" . $row["principal"]);
$cabeza = $cabeza->fetch_assoc();


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Información de familia</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Información de familia</h1>
    </div>
    <div class="datosFamilia">
        <h3><?php echo "Id: " . $row["id"] ?></h3>
        <h3><?php echo "Direccion: " . $row["direccion"] ?></h3>
        <h3><?php echo "Cabeza de familia: " . $cabeza["nombre"] . " ".$cabeza["apellidos"] ?></h3>
        <h3><?php if($row["baja"]==1) echo "Baja: Sí"; else echo "Baja: No"; ?></h3>
    </div>
    <div class="contenedor">
        <a <?php echo "href='chStatusFamilia.php?id=" . $row["id"] . "'" ?>><button
                class="action-button"><?php if ($row["baja"] == 1) {
                    echo "Dar de alta";
                } else {
                    echo "Dar de baja";
                } ?></button></a>
        <a <?php echo "href='editFamilia.php?id=" . $row["id"] . "'" ?>><button class="action-button">Editar</button></a>
    </div>


</body>

</html>