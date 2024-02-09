<?php
require('../fpdf186/fpdf.php');

try {
    $idEquipo = $_POST['idEquipo'];
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "SELECT * FROM equipos WHERE idEquipos=:idEquipo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idEquipo', $idEquipo, PDO::PARAM_STR);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    $serial = $fila['numeroSerie'];
    $nombre = $fila['nombre'];
    // Crear instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Títulos
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte General de Novedades', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Equipo #'.$serial, 0, 1, 'C'); // Reemplaza XXXX con el número de equipo
$pdf->Cell(0, 10, 'Nombre del Equipo: '.$nombre, 0, 1, 'C'); 

// Datos de la tabla (sustituye los datos y la conexión a tu base de datos)
$sql2 = "SELECT novedades.idNovedades, novedades.fechayHora, novedades.tipoNovedad, administradores.nombre as 'nombreAdmin', personal_mantenimiento.nombre 
FROM novedades 
INNER JOIN administradores ON administradores.idAdministradores=novedades.Administradores_idAdministradores 
INNER JOIN personal_mantenimiento ON personal_mantenimiento.idPersonal_Mantenimiento=novedades.Personal_Mantenimiento_idPersonal_Mantenimiento 
WHERE novedades.idEquipo=:idEquipo";
$stmt2 = $pdo->prepare($sql2);
$stmt2->bindParam(':idEquipo', $idEquipo, PDO::PARAM_STR);
$stmt2->execute();
$fila2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$pdf->Ln(); // Nueva línea
if ($stmt2->rowCount() > 0) {
    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(30, 10, 'Identificador', 1,0,"C");
    $pdf->Cell(40, 10, 'Fecha y Hora', 1,0,"C");
    $pdf->Cell(30, 10, 'Tipo', 1,0,"C");
    $pdf->Cell(40, 10, 'Persona que Registra', 1,0,"C");
    $pdf->Cell(40, 10, 'Persona de Mantenimiento', 1,0,"C");
    $pdf->Ln(); // Nueva línea

    // Datos de la tabla
    $pdf->SetFont('Arial', '', 7);
    foreach ($fila2 as $key) {
        $pdf->Cell(30, 10, $key['idNovedades'], 1,0,"C");
        $pdf->Cell(40, 10, $key['fechayHora'], 1,0,"C");
        $pdf->Cell(30, 10, $key['tipoNovedad'], 1,0,"C");
        $pdf->Cell(40, 10, $key['nombreAdmin'], 1,0,"C");
        $pdf->Cell(40, 10, $key['nombre'], 1,0,"C");
        $pdf->Ln(); // Nueva línea
    }
    
} else {
    $pdf->Cell(0, 10, 'No hay datos para mostrar', 1, 1, 'C');
}


// Salida del PDF
$pdf->Output(); 
   // echo "NUMERO DE EQUIPO: $serial </br></br> NOMBRE EQUIPO: $nombre </br></br>";
    } catch (\Throwable $th) {
        echo $th;
        //$_SESSION['error'] = "Error Contacte a Soporte";
        //header("Location: ../view/cargos.php");
    }

?>