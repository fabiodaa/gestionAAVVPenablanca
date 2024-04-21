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