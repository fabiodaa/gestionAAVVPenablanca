<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

if(isset($_GET["direccion"])){
    if(isset($_GET["domiciliado"])){
        $domiciliado=$_GET["domiciliado"];
    }
    else{
        $domiciliado=0;
    }
    $query="INSERT INTO familia (direccion,domiciliado) VALUES ('". $_GET["direccion"] ."',".$domiciliado.")";
    
    $socio = mysqli_query($conexion, $query);
    $ultimo_id_familia = mysqli_insert_id($conexion);
    $query="INSERT INTO socio (apellidos,nombre,fechaNacimiento,dni,email,familia) VALUES ('". $_GET["apellidos"] ."','". $_GET["nombre"] ."','". $_GET["fechaNacimiento"] ."','". $_GET["dni"] ."','". $_GET["email"] ."',". $ultimo_id_familia .")";
    $socio = mysqli_query($conexion, $query);
    $ultimo_id_socio= mysqli_insert_id($conexion);
    $query="UPDATE familia SET principal=". $ultimo_id_socio ." WHERE id=". $ultimo_id_familia ."";
    $socio = mysqli_query($conexion, $query);
    header("location: familia.php?id=$ultimo_id_familia");

}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Añadir familia</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <?php include 'header.php'; ?>
    <div>
        <h1>Añadir familia</h1>
    </div>
    <div class="busqueda">
        <h2>Datos</h2>
        <div class="editar">
            <form method="get" action="addFamily.php">
                <div class="campoEdicion">
                    <label for="direccion">Dirección</label>
                    <input required class="texto" placeholder="Dirección"
                        type="text" name="direccion" id="direccion">
                </div>
                <br>
                <div>
                    <label for="domiciliado">Pago domiciliado</label>
                    <input type="checkbox" id="domiciliado" name="domiciliado" value="1">
                </div>
                <br>
                <p>Cabeza de familia</p>
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
                    <a href="familias.php"><button type="button" class="confirmar">Cancelar</button></a>
                    <input class="confirmar" type="submit" value="Confirmar">
                </div>
            </form>
        </div>
    </div>


</body>

</html>