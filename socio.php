<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$credentials = leerArchivo("bbdd.txt");

$conexion = mysqli_connect($credentials[0], $credentials[1], $credentials[2], $credentials[3]);

if (isset($_GET["id"])) {
    $socio = mysqli_query($conexion, "SELECT * FROM socio WHERE id=" . $_GET["id"]);
} else {
    header("location: .");
}

$row = $socio->fetch_assoc();


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Información de socio</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Información de socio</h1>
    </div>
    <div class="contenedor">
        <div class="campo">
            <p>Número:</p>
            <p><?php echo $row["id"] ?></p>
        </div>
        <div class="campo">
            <p>Apellidos:</p>
            <p><?php echo $row["apellidos"] ?></p>
        </div>
        <div class="campo">
            <p>Nombre:</p>
            <p><?php echo $row["nombre"] ?></p>
        </div>
        <div class="campo">
            <p>Fecha de nacimiento:</p>
            <p><?php echo fechaFormato($row["fechaNacimiento"])." (".edad($row["fechaNacimiento"])." años)" ?></p>
        </div>
        <div class="campo">
            <p>DNI:</p>
            <p><?php echo $row["dni"] ?></p>
        </div>
        <div class="campo">
            <p>Email:</p>
            <p><?php echo $row["email"] ?></p>
        </div>
        <div class="campo">
            <p>Familia:</p>
            <p><?php echo $row["familia"] ?></p>
        </div>
        <div class="campo">
            <p>Baja:</p>
            <p><?php if($row["baja"]==1){echo "Sí";} else{echo "No";} ?></p>
        </div>
    <div class="contenedor">
        <a <?php echo "href='chStatus.php?id=" . $row["id"] . "'" ?>><button class="action-button"><?php if($row["baja"]==1){echo "Dar de alta";}else{echo "Dar de baja";} ?></button></a>
        <a <?php echo "href='chfamily.php?id=" . $row["id"] . "'" ?>><button class="action-button">Cambiar de familia</button></a>
    </div>
    </div>


</body>

</html>