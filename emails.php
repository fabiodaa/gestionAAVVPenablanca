<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
    exit();
}

$conexion = connect2db();


$socios = mysqli_query($conexion, "SELECT email FROM socio WHERE email IS NOT NULL");

$lista="";

while($row = $socios->fetch_assoc()) {
    $lista=$lista.$row["email"].", ";
}
$lista=substr($lista, 0, -2);
$lista = str_replace(array("\r", "\n"), '', $lista);


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Lista de emails</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Lista de emails</h1>
    </div>
    <div>
        <p><?php echo $lista ?></p>
    </div>
    

</body>

</html>