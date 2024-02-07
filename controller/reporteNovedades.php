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
$pdf->Cell(0, 20, 'Informe de Reportes PDF', 0, 1, 'C');
$pdf->Ln(30);

// Datos del formulario
$formData = array(
    'Fecha y Hora de Mantenimiento' => $_POST['fechayHora'],
    'Tipo de Novedad' => $_POST['tipoNovedad'],
    'Descripcion' => $_POST['descripcion'] ,
    'Estado de Intervencion' => strtoupper($_POST['estadoIntervencion']) ,
    'Recomendaciones' => $_POST['recomendaciones'],
    'Recomendaciones' => $_POST['recomendaciones'],
    'Equipo Afectado' => $_POST['idEquipo'],
    'Persona de Mantenimiento' => $_POST['Personal_Mantenimiento_idPersonal_Mantenimiento'],
    'Persona que Reporto' => $_POST['admin'],
);

// Agregar datos al informe
foreach ($formData as $label => $value) {
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(40, 10, mb_convert_encoding($label,'ISO-8859-1', 'UTF-8') . ':', 0, 0);
    $pdf->SetFont('Arial', '', 10);

    // Ajustar la posición X para los valores
    $pdf->SetX($pdf->GetX() + 50); // Ajusta el valor 10 según sea necesario
    $pdf->MultiCell(0, 10, $value, 0, 'L');
}

// Salida del PDF (descarga)
$pdf->Output();

?>