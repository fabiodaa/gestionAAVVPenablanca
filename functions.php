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