<?php
session_start();

    try {
    $idAdmin = $_SESSION['cedula'];
    $fechaYhora = $_POST['fechayHora'];
    $tipoNovedad = $_POST['tipoNovedad'];
    $descripcion = $_POST['descripcion'];
    $estadoIntervencion = $_POST['estadoIntervencion'];
    $recomendaciones = $_POST['recomendaciones'];
    $idEquipo = $_POST['idEquipo'];
    $personal_mantenimiento = $_POST['personal_mantenimiento'];
    $fechaBien = str_replace("T"," ",$fechaYhora);

    
    include('../model/MySQL.php');
     $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "INSERT INTO novedades (fechayHora, tipoNovedad, descripcion, estadoIntervencion, recomendaciones, idEquipo, Administradores_idAdministradores,Personal_Mantenimiento_idPersonal_Mantenimiento) VALUES (:fechayHora,:tipoNovedad, :descripcion, :estadoIntervencion, :recomendaciones, :idEquipo, :idAdmin, :idPersonal)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':fechayHora', $fechaBien, PDO::PARAM_STR);
    $stmt->bindParam(':tipoNovedad', strtoupper($tipoNovedad), PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', strtoupper($descripcion), PDO::PARAM_STR);
    $stmt->bindParam(':estadoIntervencion', $estadoIntervencion, PDO::PARAM_STR);
    $stmt->bindParam(':recomendaciones', strtoupper($recomendaciones), PDO::PARAM_STR);
    $stmt->bindParam(':idEquipo', $idEquipo, PDO::PARAM_STR);
    $stmt->bindParam(':idAdmin', $idAdmin, PDO::PARAM_STR);
    $stmt->bindParam(':idPersonal', $personal_mantenimiento, PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['felicitaciones'] = 'Novedad Agregada Correctamente Insertado Correctamente';
    header("Location: ../view/novedad.php");  
    } catch (\Throwable $th) {
        echo "FECHA: $fechaYhora </br></br> FECHA BIEN: $fechaBien </br></br> TIPO: $tipoNovedad </br></br> DESCRIPCION: $descripcion 
    </br></br> ESTADO: $estadoIntervencion </br></br> RECOMENDACIONES: $recomendaciones </br></br> ID-EQUIPO: $idEquipo </br></br> PERSONAL: $personal_mantenimiento";
        echo $th;
        /* $_SESSION['error'] = "Error al Insertar Contacte a Soporte";
        header("Location: ../view/proveedor.php"); */
    } 
?>