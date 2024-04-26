<?php
include("functions.php");

session_set_cookie_params(3600);
session_start();
if (!isset($_POST["password"])){
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    header("location: index.php");
}
else{
    $password = $_POST["password"];
    $hash=leerArchivo("credentials/pwdhash.txt")[0];
    if($hash==hash('sha256', $password)){
        $_SESSION["loggedIn"] = true;
        header("location: socios.php");
    }
    else{
        header("location: ./?error=true");
    }

}
