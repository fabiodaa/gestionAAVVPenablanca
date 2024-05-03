<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

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
    <title><?php if($row["baja"]==1){ echo "Anular baja";} else{ echo "Baja de socio"; } ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1><?php if($row["baja"]==1){ echo "Estás seguro de que quieres volver a dar de alta a ".$row["nombre"]." ".$row["apellidos"].". Ten en cuenta que la familia debe estar dada de alta para que las cuotas se calculen adecuadamente.";} else{ echo "¿Seguro que quieres dar de baja a ".$row["nombre"]." ".$row["apellidos"]."?"; } ?></h1>
    </div>
    <div class="contenedor">
        <a <?php echo "href='chStatus.php?id=" . $row["id"] . "'" ?>><button class="action-button">Cancelar</button></a>
        <a <?php echo "href='chfamily.php?id=" . $row["id"] . "'" ?>><button class="action-button">Confirmar</button></a>
    </div>
    </div>


</body>

</html>