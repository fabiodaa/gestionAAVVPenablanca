<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

if(isset($_GET["familia"])&&isset($_GET["nombre"])){
    $query="INSERT INTO socio (nombre,apellidos,dni,email,fechaNacimiento,familia) VALUES ('". $_GET["nombre"] ."','". $_GET["apellidos"] ."','". $_GET["dni"] ."','". $_GET["email"] ."','". $_GET["fechaNacimiento"] ."',". $_GET["familia"] .")";
    $socio = mysqli_query($conexion, $query);
    header("location: familia.php?id=".$_GET["familia"]);

}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Añadir socio</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Añadir socio</h1>
    </div>
    <div class="busqueda">
        <h2>Datos</h2>
        <div class="editar">
            <form method="get" action="addSocio.php">
                <input type="hidden" name="familia" value="<?php echo $_GET["familia"]; ?>">
                <div class="campoEdicion">
                    <label for="nombre">Nombre</label>
                    <input required class="texto" placeholder="Nombre"
                        type="text" name="nombre" id="nombre">
                </div>
                <div class="campoEdicion">
                    <label for="apellidos">Apellidos</label>
                    <input required class="texto"
                        placeholder="Apellidos" type="text" name="apellidos" id="apellidos"><br>
                </div>
                <div class="campoEdicion">
                    <label for="fechaNac">Fecha de nacimiento</label>
                    <input required class="texto"
                        placeholder="Fecha de nacimiento" type="date" name="fechaNacimiento" id="fechaNacimiento"><br>
                </div>
                <div class="campoEdicion">
                    <label for="dni">DNI</label>
                    <input class="texto" placeholder="DNI"
                        type="text" name="dni" id="dni"><br>
                </div>
                <div class="campoEdicion">
                    <label for="email">Email</label>
                    <input class="texto" placeholder="E-Mail"
                        type="text" name="email" id="email"><br>
                </div>
                <div class="campoEdicion">
                    <a href="<?php echo "familia.php?id=".$_GET["familia"]?>"><button type="button" class="confirmar">Cancelar</button></a>
                    <input class="confirmar" type="submit" value="Confirmar">
                </div>
            </form>
        </div>
    </div>


</body>

</html>