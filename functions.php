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
    $sql="SELECT fechaNacimiento,anioRegistro FROM socio WHERE familia=".$familia. " AND baja=0";
    $familia = mysqli_query($conexion,$sql);
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
function getEstadoPago($familia,$conexion){


    $sql="SELECT id,ultimoAnioPagado FROM familia WHERE id=".$familia;
    $ultimoPago="SELECT * FROM pago WHERE familia=".$familia. " ORDER BY fecha DESC LIMIT 1";
    $familia = mysqli_query($conexion,$sql);
    $row = $familia->fetch_assoc();
    $ultimoPagoQuery = mysqli_query($conexion,$ultimoPago);
    $rowPago = $ultimoPagoQuery->fetch_assoc();
    if($row["ultimoAnioPagado"]==date("Y")){
        if(calcularCuota($row["id"],$conexion)==$rowPago["cantidad"]){
            $estado="Pagado";
        }else $estado="Falta parte";
    }else $estado="Pendiente";
    
    return $estado;
}

function getCuotaRestante($id,$cuota,$conexion){
    $restante=$cuota;

    $pagos= mysqli_query($conexion,"SELECT * FROM pago WHERE familia=".$id . " AND anio=".date("Y"));
    while($pago = $pagos->fetch_assoc()) {
        $restante-=$pago["cantidad"];
    }
    return $restante;
}

function getDesglose($familia,$conexion){
    $desglose="";

    $cuotas= mysqli_query($conexion,"SELECT * FROM tarifa");
    $sql="SELECT fechaNacimiento,anioRegistro FROM socio WHERE familia=".$familia. " AND baja=0";
    $familia = mysqli_query($conexion,$sql);
    while($row = $cuotas->fetch_assoc()) {
        $familia->data_seek(0);
        $cuota=$row["tarifa"];
        $num=0;
        while($row_miembro = $familia->fetch_assoc()) {
            $edad=edad($row_miembro["fechaNacimiento"]);
            if($row["nuevo"]==0 && $edad>=$row["edadMin"] && $edad<$row["edadMax"]){
                $num++;
            }
            else if($row["nuevo"]==1 && $edad>=$row["edadMin"] && $edad<$row["edadMax"]){
                $anio_actual = date('Y');
                if($anio_actual==$row_miembro["anioRegistro"]){
                    $num++;
                }
            }
        }
        if($num>0 && $row["nuevo"]==1){
            $desglose.=$num." x cuotas nuevo socio - mayores de ".$row["edadMin"]." años(".$row["tarifa"]." €),";
        } 
        else if($num>0 && $row["edadMax"]==255){
            $desglose.=$num." x + de".$row["edadMin"]." años(".$row["tarifa"]." €),";
        }
        else if($num>0){
            $desglose.=$num." x ".$row["edadMin"]."-".$row["edadMax"]." años(".$row["tarifa"]." €),";
        }

    }
    return $desglose;
}
