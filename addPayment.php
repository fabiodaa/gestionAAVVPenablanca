<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

if(isset($_GET["familia"])&&isset($_GET["cantidad"])){
    $query="INSERT INTO pago (familia,cantidad,anio,fecha,metodo,desglose) VALUES (". $_GET["familia"] .",". $_GET["cantidad"] .",". $_GET["anio"] .",'". $_GET["fecha"] ."','". $_GET["metodo"] ."','". $_GET["desglose"] ."')";
    $socio = mysqli_query($conexion, $query);
    $query="UPDATE familia SET ultimoAnioPagado=".$_GET["anio"]." WHERE id=".$_GET["familia"]." AND (ultimoAnioPagado<".$_GET["anio"]." OR ultimoAnioPagado IS NULL)";
    $socio=mysqli_query($conexion,$query);
    header("location: pago.php?id=".$_GET["familia"]);

}

