<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

if (isset($_GET["socio"])&&isset($_GET["familia"])){
    $conexion -> query("UPDATE familia SET principal=".$_GET["socio"] . " WHERE id=".$_GET["familia"]."");
    header("location: familia.php?id=". $_GET["familia"] ."");
}