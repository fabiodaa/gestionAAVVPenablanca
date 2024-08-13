<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
    exit();
}

$conexion = connect2db();

if (isset($_GET["id"])) {
    $socio = mysqli_query($conexion, "SELECT * FROM socio WHERE id=" . $_GET["id"]);
} else {
    header("location: .");
}

$row = $socio->fetch_assoc();

if (isset($_GET["id"]) && isset($_GET["nombre"])){
    $conexion -> query("UPDATE socio SET nombre='".$_GET["nombre"] . "',apellidos='".$_GET["apellidos"] . "',fechaNacimiento='".$_GET["fechaNacimiento"] . "',dni='".$_GET["dni"] . "',email='".$_GET["email"] . "' WHERE id=". $row["id"]."");
    header("location: socio.php?id=". $row["id"] ."");
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Editar socio</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Editar socio</h1>
    </div>
    <div class="busqueda">
        <h2>Datos</h2>
        <div class="editar">
            <form method="get" action="editSocio.php">
                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                <div class="campoEdicion">
                    <label for="nombre">Nombre</label>
                    <input required class="texto" <?php echo 'value="' . $row["nombre"] . '"'; ?> placeholder="Nombre"
                        type="text" name="nombre" id="nombre">
                </div>
                <div class="campoEdicion">
                    <label for="apellidos">Apellidos</label>
                    <input required class="texto" <?php echo 'value="' . $row["apellidos"] . '"'; ?>
                        placeholder="Apellidos" type="text" name="apellidos" id="apellidos"><br>
                </div>
                <div class="campoEdicion">
                    <label for="fechaNac">Fecha de nacimiento</label>
                    <input required class="texto" <?php echo 'value="' . $row["fechaNacimiento"] . '"'; ?>
                        placeholder="Fecha de nacimiento" type="date" name="fechaNacimiento" id="fechaNacimiento"><br>
                </div>
                <div class="campoEdicion">
                    <label for="dni">DNI</label>
                    <input class="texto" <?php echo 'value="' . $row["dni"] . '"'; ?> placeholder="DNI"
                        type="text" name="dni" id="dni"><br>
                </div>
                <div class="campoEdicion">
                    <label for="email">Email</label>
                    <input class="texto" <?php echo 'value="' . $row["email"] . '"'; ?> placeholder="E-Mail"
                        type="text" name="email" id="email"><br>
                </div>
                <div class="campoEdicion">
                    <a href="socio.php?id=<?php echo $row["id"] ?>"><button type="button" class="confirmar">Cancelar</button></a>
                    <input class="confirmar" type="submit" value="Confirmar">
                </div>
            </form>
        </div>
    </div>


</body>

</html>