<?php
    session_set_cookie_params(3600);
    session_start();
    if (isset ($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]==true)  {
        header("location: socios.php");
    }

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Inicio de Sesión</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/index.css">
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
            <?php
                # Muestra el mensaje de contraseña incorrecta si es necesario
                if (isset($_GET["error"])&&$_GET["error"]==true){
                    ?>
                    <input class="texto-error" required placeholder="Contraseña" type="password" name="password" id="password">
                    <p class="error">Contraseña incorrecta</p>
                    <?php
                }else{
                    ?>
                    <input class="texto" autocomplete="current-password" required placeholder="Contraseña" type="password" name="password" id="password">
                    <?php
                }
            ?>
            <input class="confirmar" type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>

</html>