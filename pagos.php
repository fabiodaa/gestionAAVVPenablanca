<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

if(!isset($_GET["nombre"]) || !isset($_GET["apellidos"]) || !isset($_GET["estado"]) || !isset($_GET["ordenar"])){
    $socios=mysqli_query($conexion,"SELECT * FROM cabezaFamilia ORDER BY apellidos");
}
else{
    if($_GET["estado"]=="todos"){
        $socios=mysqli_query($conexion,"SELECT * FROM cabezaFamilia WHERE nombre LIKE '" . $_GET['nombre'] . "%' AND apellidos LIKE '%" . $_GET['apellidos'] . "%' AND direccion LIKE '%" . $_GET['direccion'] . "%' ORDER BY ".$_GET['ordenar']);
    }
    if($_GET["estado"]=="alta"){
        $socios=mysqli_query($conexion,"SELECT * FROM cabezaFamilia WHERE baja=0 AND nombre LIKE '" . $_GET['nombre'] . "%' AND apellidos LIKE '%" . $_GET['apellidos'] . "%' AND direccion LIKE '%" . $_GET['direccion'] . "%' ORDER BY ".$_GET['ordenar']);
    }
    if($_GET["estado"]=="baja"){
        $socios=mysqli_query($conexion,"SELECT * FROM cabezaFamilia WHERE baja=1 AND nombre LIKE '" . $_GET['nombre'] . "%' AND apellidos LIKE '%" . $_GET['apellidos'] . "%' AND direccion LIKE '%" . $_GET['direccion'] . "%' ORDER BY ".$_GET['ordenar']);
    }
}



?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Pagos</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Pagos</h1>
    </div>
    <div class="busqueda">
        <a href="addFamily.php"><button class="anadir">Nueva familia</button></a>
        <h3>Filtros</h3>
        <div class="barrabusqueda">
            <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input class="texto" <?php if(isset($_GET['nombre'])){ echo 'value="' . $_GET["nombre"] . '"'; } ?> placeholder="Nombre" type="text" name="nombre" id="nombre">
                <input class="texto" <?php if(isset($_GET['apellidos'])){ echo 'value="' . $_GET["apellidos"] . '"'; } ?> placeholder="Apellidos" type="text" name="apellidos" id="apellidos"><br>
                <input class="texto" <?php if(isset($_GET['direccion'])){ echo 'value="' . $_GET["direccion"] . '"'; } ?> placeholder="Dirección" type="text" name="direccion" id="direccion"><br>
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
                <label for="num">Nº de familia</label>
                <input class="opcion" <?php if(isset($_GET['ordenar']) && $_GET['ordenar'] == 'direccion') echo ' checked'; ?> type="radio" id="direccion" name="ordenar" value="direccion">
                <label for="fecha">Dirección</label><br>
                <input class="confirmar" type="submit" value="Buscar">
            </form>
        </div>
    </div>
    <div class="listaSocios">
        <h3>Lista de socios</h3>
        <?php
            if($socios->num_rows > 0){
                echo "<table class=\"socios\" border='1'>";
                echo "<tr>";
                echo "<th>Nº</th>";
                echo "<th>Dirección</th>";
                echo "<th>Cabeza de familia</th>";
                echo "<th>Ver</th>";
                echo "</tr>";
                while($row = $socios->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["direccion"] . "</td>";
                    echo "<td>" . $row["nombre"] . " ". $row["apellidos"] ."</td>";
                    echo "<td><a href='familia.php?id=" . $row["id"] . "'><button class=\"ver\">Ver</button></a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }else{
                ?> <h3>No hay resultados</h3><?php
            }
        ?>
    </div>
</body>

</html>