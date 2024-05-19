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

if(isset($_GET["direccion"])&&isset($_GET["id"])){
    $conexion -> query("UPDATE familia SET direccion='". $_GET["direccion"] ."' WHERE id=". $_GET["id"]);
    header("location: familia.php?id=". $_GET["id"]);
}

if (isset($_GET["id"])) {
    $familia = mysqli_query($conexion, "SELECT * FROM familia WHERE id=" . $_GET["id"]);

} else {
    header("location: .");
}

$row = $familia->fetch_assoc();
$socios=mysqli_query($conexion,"SELECT * FROM socio WHERE familia=". $row["id"]."");
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
        <h3><?php echo "Cabeza de familia: " . $cabeza["nombre"] . " " . $cabeza["apellidos"] ?></h3>
        <h3><?php if ($row["baja"] == 1)
            echo "Baja: Sí";
        else
            echo "Baja: No"; ?></h3>
    </div>
    <div class="contenedor">
        <a <?php echo "href='chStatusFamilia.php?id=" . $row["id"] . "'" ?>><button class="action-button"><?php if ($row["baja"] == 1) {
                   echo "Dar de alta";
               } else {
                   echo "Dar de baja";
               } ?></button></a>
        <a <?php echo "href='addSocio.php?familia=" . $row["id"] . "'" ?>><button class="action-button">Añadir socio</button></a>
    </div>
    <div>
        <h3>Editar direccion</h3>
        <form method="get" action="familia.php">
            <div>
                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                <input required class="campoDireccion" <?php echo 'value="' . $row["direccion"] . '"'; ?> placeholder="Dirección"
                    type="text" name="direccion" id="direccion">
            </div>
            <div>
                <input class="confirmar" type="submit" value="Confirmar">
            </div>
        </form>
    </div>
    <div class="listaSocios">
        <h3>Lista de socios</h3>
        <?php
            if($socios->num_rows > 0){
                echo "<table class=\"socios\" border='1'>";
                echo "<tr>";
                echo "<th>Nº</th>";
                echo "<th>Apellidos</th>";
                echo "<th>Nombre</th>";
                echo "<th>Fecha nacimiento</th>";
                echo "<th>Ver</th>";
                echo "<th>Hacer cabeza de familia</th>";
                echo "</tr>";
                while($socio = $socios->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $socio["id"] . "</td>";
                    echo "<td>" . $socio["apellidos"] . "</td>";
                    echo "<td>" . $socio["nombre"] . "</td>";
                    echo "<td>" . fechaFormato($socio["fechaNacimiento"])." (".edad($socio["fechaNacimiento"])." años)" . "</td>";
                    echo "<td><a href='socio.php?id=" . $socio["id"] . "'><button class=\"ver\">Ver</button></a></td>";
                    if($socio["id"]==$row["principal"]) echo "<td>Ya lo es</td>";
                    elseif($socio["baja"]==1) echo "<td>Está de baja</td>";
                    else echo "<td><a href='chCabeza.php?socio=" . $socio["id"] . "&familia=". $row["id"] ."'><button class=\"principal\">Hacer cabeza de familia</button></a></td>";
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