<?php
    session_start();
    if (isset ($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]==true)  {
        header("location: home.php");
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Inicio de Sesión</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/logo.png">
</head>

<body>
    <div class="header">
        <h1>Asociación de Vecinos Peñablanca</h1>
    </div>
    <div class="header">
        <img src="assets/logo.png" alt="Logo"> 
    </div>
    <div class="content">
        <form method="post" action="login.php">
            <input class="texto" required placeholder="Contraseña" type="password" name="password" id="password" required>
            <?php
                if (isset($_GET["error"])&&$_GET["error"]==true){
                    ?> <p class="error">Contraseña incorrecta</p><?php
                }
            ?>
            <input class="confirmar" type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>

</html>