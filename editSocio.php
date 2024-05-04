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
        <h3>Datos</h3>
        <div class="editar">
            <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input class="texto" <?php echo 'value="' . $row["nombre"] . '"'; ?> placeholder="Nombre" type="text" name="nombre" id="nombre">
                <input class="texto" <?php echo 'value="' . $_GET["apellidos"] . '"'; ?> placeholder="Apellidos" type="text" name="apellidos" id="apellidos"><br>
                <p class="etiqueta">Mostrar</p>
                <input class="opcion" <?php if(isset($_GET['estado']) && $_GET['estado'] == 'alta') echo ' checked'; ?> type="radio" id="alta" name="estado" value="alta">
                <label for="alta">Alta</label>
                <input class="opcion" <?php if(isset($_GET['estado']) && $_GET['estado'] == 'baja') echo ' checked'; ?> type="radio" id="baja" name="estado" value="baja">
                <label for="baja">Baja</label>
                <input class="opcion" <?php if(!isset($_GET['estado']) || $_GET['estado'] == 'todos') echo ' checked'; ?> type="radio" id="todos" name="estado" value="todos">
                <label for="todos">Todos</label><br>
                <p class="etiqueta">Ordenar por</p>
                <input class="opcion" <?php if(isset($_GET['ordenar']) && $_GET['ordenar'] == 'nombre') echo ' checked'; ?> type="radio" id="nombre" name="ordenar" value="nombre">
                <label for="nombre">Nombre</label>
                <input class="opcion" <?php if(!isset($_GET['ordenar']) || $_GET['ordenar'] == 'apellidos') echo ' checked'; ?> type="radio" id="apellidos" name="ordenar" value="apellidos">
                <label for="apellidos">Apellidos</label>
                <input class="opcion" <?php if(isset($_GET['ordenar']) && $_GET['ordenar'] == 'id') echo ' checked'; ?> type="radio" id="num" name="ordenar" value="id">
                <label for="num">Nº de socio</label>
                <input class="opcion" <?php if(isset($_GET['ordenar']) && $_GET['ordenar'] == 'fechaNacimiento') echo ' checked'; ?> type="radio" id="fecha" name="ordenar" value="fechaNacimiento">
                <label for="fecha">Fecha de nacimiento</label><br>
                <input class="confirmar" type="submit" value="Buscar">
            </form>
        </div>
    </div>
    </div>


</body>

</html>