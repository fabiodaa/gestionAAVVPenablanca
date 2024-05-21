<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

$familias=mysqli_query($conexion,"SELECT * FROM cabezaFamilia WHERE baja=0 ORDER BY apellidos");


if(isset($_GET["id"])&&isset($_GET["newFamily"])){
    $query="UPDATE socio SET familia=". $_GET["newFamily"] ." WHERE id=".$_GET["id"]."";
    $socio = mysqli_query($conexion, $query);
    header("location: socio.php?id=".$_GET["id"]);

}

if(isset($_GET["id"])&&isset($_GET["create"])&&isset($_GET["direccion"])){
    if($_GET["create"]==true){
        $query="INSERT into familia(direccion,principal) VALUES ('". $_GET["direccion"]."',".$_GET["id"].")";
        $socio = mysqli_query($conexion, $query);
        $ultimo_id_familia = mysqli_insert_id($conexion);
        $query="UPDATE socio SET familia=".$ultimo_id_familia." WHERE id=".$_GET["id"]."";
        $socio = mysqli_query($conexion, $query);
    }

    header("location: socio.php?id=".$_GET["id"]);

}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Cambiar de familia</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Cambiar de familia</h1>
    </div>
    <div class="busqueda">
        <h2>Nueva familia</h2>
        <div class="editar">
            <form method="get" action="chFamily.php">
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
                <input type="hidden" name="create" value="true">
                <div class="campoEdicion">
                    <label for="direccion">Dirección</label>
                    <input required class="texto" placeholder="Dirección"
                        type="text" name="direccion" id="direccion">
                </div>
                <div class="campoEdicion">
                    <a href="<?php echo "socio.php?id=".$_GET["id"]?>"><button type="button" class="confirmar">Cancelar</button></a>
                    <input class="confirmar" type="submit" value="Confirmar">
                </div>
            </form>
        </div>
        <h2>Familia existente</h2>
        <div class="editar">
            <form method="get" action="chFamily.php">
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
                <div class="campoEdicion">
                    <label for="newFamily">Familia</label>
                    <select class="confirmar" name="newFamily" id="newFamily">
                    <?php 
                    while($row = $familias->fetch_assoc()) {
                        echo "<option value='".$row["id"]."'>".$row["direccion"]." - ".$row["nombre"]." ".$row["apellidos"]."</option>";
                    }
                    ?>
                </div>
                <div class="campoEdicion">
                    <a href="<?php echo "socio.php?id=".$_GET["id"]?>"><button type="button" class="confirmar">Cancelar</button></a>
                    <input class="confirmar" type="submit" value="Confirmar">
                </div>
            </form>
        </div>
    </div>


</body>

</html>