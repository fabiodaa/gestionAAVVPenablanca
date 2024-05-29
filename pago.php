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
    $pagos = mysqli_query($conexion, "SELECT * FROM pago WHERE familia=" . $_GET["id"] . " ORDER BY fecha DESC");

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
$cabeza = mysqli_query($conexion, "SELECT nombre,apellidos FROM socio WHERE id=" . $row["principal"]);
$cabeza = $cabeza->fetch_assoc();

$cuota=calcularCuota($row["id"],$conexion);
$restante=getCuotaRestante($row["id"],$cuota,$conexion);
$desglose=getDesglose($row["id"],$conexion);


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Información de pago</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Información de pago</h1>
    </div>
    <div class="datosFamilia">
        <h3><?php echo "Id: " . $row["id"] ?></h3>
        <h3><?php echo "Direccion: " . $row["direccion"] ?></h3>
        <h3><?php echo "Cabeza de familia: " . $cabeza["nombre"] . " " . $cabeza["apellidos"] ?></h3>
        <h3><?php echo "Cuota ". date('Y') .": " . $cuota. " €" ?></h3>
        <h3><?php echo "Pendiente de pago: ".$restante." €" ?></h3>
        <h3><?php if ($row["ultimoAnioPagado"] == NULL)
            echo "Último año pagado: Ninguno";
        else
            echo "Último año pagado: ".$row["ultimoAnioPagado"]; ?></h3>
        <h3><?php if ($row["baja"] == 1)
            echo "Baja: Sí";
        else
            echo "Baja: No"; ?></h3>
        <h3><?php echo "Estado pago ".date("Y")." : ".getEstadoPago($row["id"],$conexion) ?></h3>
    </div>
    <div class="busqueda">
        <h2>Añadir nuevo pago</h2>
        <div class="editar">
            <form method="get" action="addPayment.php">
                <input type="hidden" name="familia" value="<?php echo $_GET["id"]; ?>">
                <div class="campoEdicion">
                    <label for="cantidad">Cantidad</label>
                    <input required class="texto" placeholder="Cantidad" value="<?php echo $restante ?>"
                        type="text" name="cantidad" id="cantidad">
                </div>
                <div class="campoEdicion">
                    <label for="anio">Año</label>
                    <input required class="texto" value="<?php echo date("Y") ?>"
                        placeholder="Año" type="text" name="anio" id="anio"><br>
                </div>
                <div class="campoEdicion">
                    <label for="fecha">Fecha del pago</label>
                    <input required class="texto" value="<?php echo date("Y-m-d") ?>"
                        placeholder="Fecha" type="date" name="fecha" id="fecha"><br>
                </div>
                <p class="etiqueta">Método de pago</p>
                <input class="opcion" type="radio" id="metodo" name="metodo" value="Efectivo">
                <label for="efectivo">Efectivo</label>
                <input class="opcion" checked type="radio" id="metodo" name="metodo" value="Transferencia">
                <label for="transferencia">Transferencia</label>
                <div class="campoEdicion">
                    <label for="desglose">Desglose</label>
                    <input class="texto" placeholder="Desglose" value="<?php echo $desglose ?>"
                        type="text" name="desglose" id="desglose"><br>
                </div>
                <div class="campoEdicion">
                    <a href="pagos.php"><button type="button" class="confirmar">Cancelar</button></a>
                    <input class="confirmar" type="submit" value="Confirmar">
                </div>
            </form>
        </div>
    </div>
    <div class="listaSocios">
        <h3>Historial de pagos</h3>
        <?php
            if($pagos->num_rows > 0){
                echo "<table class=\"socios\" border='1'>";
                echo "<tr>";
                echo "<th>Cantidad</th>";
                echo "<th>Año</th>";
                echo "<th>Fecha</th>";
                echo "<th>Método</th>";
                echo "<th>Desglose</th>";
                echo "</tr>";
                while($pago = $pagos->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $pago["cantidad"] . "</td>";
                    echo "<td>" . $pago["anio"] . "</td>";
                    echo "<td>" . fechaFormato($pago["fecha"]) . "</td>";
                    echo "<td>" . $pago["metodo"] ."</td>";
                    echo "<td>" . $pago["desglose"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }else{
                ?> <h3>No hay pagos previoss</h3><?php
            }
        ?>
    </div>


</body>

</html>