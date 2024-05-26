<?php
include ("functions.php");

session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"] == true) {
    header("location: .");
}

$conexion = connect2db();

$familias=mysqli_query($conexion,"SELECT * FROM cabezaFamilia WHERE baja=0 ORDER BY apellidos");
$tarifas=mysqli_query($conexion,"SELECT * FROM tarifa ORDER BY nuevo,edadMin");

// Incluir el archivo autoload de Composer para cargar TCPDF
require_once('vendor/autoload.php');

// Extender la clase TCPDF para crear encabezado y pie de página personalizados
class MYPDF extends TCPDF {
    // Encabezado de página
    public function Header() {
        // Logo
        $logo = 'assets/logo_black.png'; // Asegúrate de que la ruta sea correcta
        $this->Image($logo, 15, 5, 25.37,20);
        // Establecer fuente
        $this->SetFont('times', 'B', 8);
        // Moverse a la derecha
        $this->SetX(-70);
        
        // Título
        $titulo = "ASOCIACIÓN DE VECINOS\n“PEÑABLANCA”\nC.I.F.:G4013369\nCtra. TRESCASAS  Nº 39\nSAN CRISTOBAL DE SEGOVIA\n40197 SEGOVIA";
        
        // Imprimir el título (varias líneas)
        $this->MultiCell(0, 5, $titulo, 0, 'C', 0, 1, '', '', true, 0, false, true, 0, 'T', false);
        // Título
    }

    // Pie de página
    public function Footer() {
        $footer="www.peñablanca.es   Ctra. Trescasas Nº 39 San Cristóbal de Segovia 40197 (Segovia). Para actualizar los datos de todos los socios, mándanos tu dirección completa, fecha de nacimiento y correo electrónico, y te mandaremos todas las noticias al correo asociacion@penablanca.es";
        $this->MultiCell(0, 0, $footer, 0, 'C', 0, 1, '', '', true, 0, false, true, 0, 'T', false);

    }
}


// Crear una instancia de TCPDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer la información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asociación de vecinos Peñablanca');
$pdf->SetTitle('Informe de cuotas '.date("d/m/Y"));
$pdf->SetSubject('Informe de cuotas por familia en activo en la asociación');
$pdf->SetKeywords('cuotas, penablanca');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(15);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

while($familia = $familias->fetch_assoc()) {
    $miembros=mysqli_query($conexion,"SELECT anioRegistro,fechaNacimiento FROM socio WHERE baja=0 AND familia=".$familia["id"]);
    $tarifas->data_seek(0);
    $total=0;
    // Añadir una página
    $pdf->AddPage();


    // Establecer la imagen del logo (ajusta la ruta a tu archivo de logo)
    $logo = 'assets/logo_black.png'; // Asegúrate de que la ruta sea correcta

    // Establecer la fuente para el título
    $pdf->SetFont('times', 'B', 20);

    // Título en el centro
    $pdf->Cell(0, 40, 'ESTIMAD@  SOCI@ '.$familia["nombre"]." ".$familia["apellidos"], 0, 1, 'C');

    // Establecer la fuente para el contenido
    $pdf->SetFont('times', '', 12);

    // Añadir contenido
    $texto = "Como sabrás la forma de pago de la CUOTA ANUAL de esta Asociación se realiza mediante el ingreso del importe en la cuenta que tiene abierta en <b>CAIXABANK OFICINA SAN CRISTOBAL.</b><br><br>Deberás especificar en el concepto del pago el nombre del socio que paga la cuota.<br><br>A continuación tienes el número de cuenta y el importe de la <b>cuota anual de ".date("Y").":</b><br>";
    $pdf->MultiCell(0, 10, $texto, 0, 'L', 0, 1, '', '', true, 0, true, true, 0, 'T', false);
    while($tarifa = $tarifas->fetch_assoc()) {
        $miembros->data_seek(0);
        $num=0;
        while($miembro = $miembros->fetch_assoc()) {
            $edad=edad($miembro["fechaNacimiento"]);
            if($tarifa["nuevo"]==0 && $edad>=$tarifa["edadMin"] && $edad<$tarifa["edadMax"]){
                $num++;
                $total+=$tarifa["tarifa"];
            }
            else if($tarifa["nuevo"]==1 && $edad>=$tarifa["edadMin"] && $edad<$tarifa["edadMax"] && $miembro["anioRegistro"]==date("Y")){
                $num++;
                $total+=$tarifa["tarifa"];
            }
        }
        if($tarifa["nuevo"]==0 && $tarifa["edadMax"]==255){
            $texto = "- ". $num." socio(s) mayores de ".$tarifa["edadMin"]." años - ".$tarifa["tarifa"]." € cada uno";
            $pdf->MultiCell(0, 10, $texto, 0, 'L', 0, 1, '', '', true, 0, true, true, 0, 'T', false);
        }
        else if($tarifa["nuevo"]==0){
            $texto = "- ". $num." socio(s) entre ".$tarifa["edadMin"]." y ".$tarifa["edadMax"]." años - ".$tarifa["tarifa"]." € cada uno";
            $pdf->MultiCell(0, 10, $texto, 0, 'L', 0, 1, '', '', true, 0, true, true, 0, 'T', false);
        }
        
        else if($tarifa["nuevo"]==1){
            $texto = "- ".$num." cuota(s) nuevo socio (mayores de".$tarifa["edadMin"]." años) - ".$tarifa["tarifa"]." € cada uno más cuota anual";
            $pdf->MultiCell(0, 10, $texto, 0, 'L', 0, 1, '', '', true, 0, true, true, 0, 'T', false);
        }

$pdf->SetFont('times', '', 15);


    }

    // Añadir contenido
    $texto = "<b><u>Nº DE CUENTA:</u> ES12 2100 7283 8113 0014 3252<br><u>IMPORTE TOTAL:</u> ".$total." €</b>";
    $pdf->MultiCell(0, 10, $texto, 0, 'L', 0, 1, '', '', true, 0, true, true, 0, 'T', false);


    $texto = "<br><br><b><u>Domicilio actual:</u></b> ".$familia["direccion"]." <br><br>Si has cambiado de domicilio notifícalo para que te pueda llegar nuestro periódico.";
    $pdf->MultiCell(0, 10, $texto, 0, 'L', 0, 1, '', '', true, 0, true, true, 0, 'T', false);

}



// Salida del PDF (puede ser descarga o visualización en el navegador)
$pdf->Output("cuotas ".date("Y").".pdf", 'I'); // 'I' para inline, 'D' para descarga

