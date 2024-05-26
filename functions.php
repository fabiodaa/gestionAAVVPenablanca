<?php

#Lee un archivo de texto y devuelve sus lineas como un array
function leerArchivo($nombreArchivo) {
    // Array para almacenar las líneas del archivo
    $lineas = array();

    // Abrir el archivo en modo lectura
    $archivo = fopen($nombreArchivo, "r");

    // Leer el archivo línea por línea hasta llegar al final
    while (($linea = fgets($archivo)) !== false) {
        $lineas[] = trim($linea);
    }
    // Cerrar el archivo
    fclose($archivo);

    return $lineas;
}
//Calcula la edad de una persona
function edad($fechaNacimiento){
    $hoy = new DateTime();
    $fechaNacimiento = new DateTime($fechaNacimiento);
    $edad = $hoy->diff($fechaNacimiento);
    return $edad->y;
}
//Convierte una fecha a formato dd/mm/YYYY
function fechaFormato($fecha){
    $fecha_time=new DateTime($fecha);
    return date_format($fecha_time,"d/m/Y");
}
function connect2db(){
    $credentials = leerArchivo("credentials/bbdd.txt");
    $conexion = mysqli_init();
    mysqli_ssl_set($conexion,'credentials/client-key.pem', 'credentials/client-cert.pem', 'credentials/ca.pem',null,null);

    mysqli_real_connect($conexion,$credentials[0], $credentials[1], $credentials[2], $credentials[3], null, null, MYSQLI_CLIENT_SSL | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);

    return $conexion;
}
function calcularCuota($familia,$conexion){
    $cuota=0;

    $cuotas= mysqli_query($conexion,"SELECT * FROM tarifa");
    $familia = mysqli_query($conexion,"SELECT fechaNacimiento,anioRegistro FROM socio WHERE familia=".$familia. " AND baja=0");
    while($row = $familia->fetch_assoc()) {
        $cuotas->data_seek(0);
        $edad=edad($row["fechaNacimiento"]);
        while($row_cuota = $cuotas->fetch_assoc()) {
            if($row_cuota["nuevo"]==0 && $edad>=$row_cuota["edadMin"] && $edad<$row_cuota["edadMax"]){
                $cuota=$cuota+$row_cuota["tarifa"];
            }
            else if($row_cuota["nuevo"]==1 && $edad>=$row_cuota["edadMin"] && $edad<$row_cuota["edadMax"]){
                $anio_actual = date('Y');
                if($anio_actual==$row["anioRegistro"]){
                    $cuota=$cuota+$row_cuota["tarifa"];
                }
            }
        }

    }
    return $cuota;
}
