<?php
require('../fpdf186/fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $this->Image('../assets/img/Logo_Bellavista.jpg.jpeg', 20, 8, 33);
        // Logo y nombre de la empresa
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 10, 'INVERSIONES BELLAVISTA', 0, 0, 'C');
        $this->Ln(20);
    }

    // Pie de página
    function Footer()
    {
        // Información legal y fecha de descarga
        $this->SetY(-25);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, mb_convert_encoding('Información Legal de la Empresa', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
        $this->Ln();
        $this->Cell(0, 10, 'Fecha y Hora de Descarga: ' . date('Y-m-d H:i:s'), 0, 0, 'C');
    }
}

// Crear instancia de PDF
$pdf = new PDF();
$pdf->AddPage();

// Contenido del informe (reemplaza con tus datos)
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 20, 'Informe Sobre Equipo #'.$_POST['numeroSerie'], 0, 1, 'C');
$pdf->Ln(30);

// Datos del formulario
$formData = array(
    'Nombre' => $_POST['nombre'],
    'Numero de Serie' => $_POST['numeroSerie'],
    'Fecha de Adquisicion' => $_POST['fechaAdquisicion'],
    '¿ Cuenta con Garantia ?' => ($_POST['garantia'] == 0) ? 'Sí' : 'No',
    'Fecha del Ultimo Mantenimiento' => $_POST['ultimoMantenimiento'],
    'Valor Comercial' => $_POST['valor'],
    'Estado' => $_POST['estado'],
    'Usuario a Cargo' => $_POST['usuario'],
    'Proveedor del Producto' => $_POST['proveedor'],
);

// Agregar datos al informe
foreach ($formData as $label => $value) {
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(40, 10, mb_convert_encoding($label,'ISO-8859-1', 'UTF-8') . ':', 0, 0);
    $pdf->SetFont('Arial', '', 15);

    // Ajustar la posición X para los valores
    $pdf->SetX($pdf->GetX() + 50); // Ajusta el valor 10 según sea necesario
    $pdf->MultiCell(0, 10, $value, 0, 'L');
}

// Salida del PDF (descarga)
$pdf->Output();

// Función para obtener el estado según el código
function obtenerEstado($codigoEstado) {
    switch ($codigoEstado) {
        case 0: return 'En Uso';
        case 1: return 'En mantenimiento';
        case 2: return 'En Desuso';
        case 3: return 'En proceso de configuración';
        case 4: return 'Retirado del inventario';
        case 5: return 'Dañada';
        case 7: return 'Apagado';
    }
}
?>