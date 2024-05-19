<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

if(isset($_GET["id"])&&isset($_GET["newFamily"])){
    $query="UPDATE socio SET familia=". $_GET["newFamily"];
    $socio = mysqli_query($conexion, $query);
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
                    <label for="opciones">Familia</label>
                    <select class="confirmar" name="opciones" id="opciones">
                    <option value="opcion1">Opción 1</option>
                    <option value="opcion2">Opción 2</option>
                    <option value="opcion3">Opción 3</option>
                    <option value="opcion4">Opción 4</option>
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