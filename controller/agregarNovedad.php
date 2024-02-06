<?php
session_start();

    try {
    $fechaYhora = $_POST['fechayHora'];
    $tipoNovedad = $_POST['tipoNovedad'];
    $descripcion = $_POST['descripcion'];
    $estadoIntervencion = $_POST['estadoIntervencion'];
    $recomendaciones = $_POST['recomendaciones'];
    $idEquipo = $_POST['idEquipo'];
    $personal_mantenimiento = $_POST['personal_mantenimiento'];
    
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "INSERT INTO proveedor (idProveedor, nombre, estado) VALUES (NULL, :nombre, 0);";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', strtoupper($proveedor), PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['felicitaciones'] = 'Proveedor Insertado Correctamente';
    header("Location: ../view/proveedor.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Error al Insertar Contacte a Soporte";
        header("Location: ../view/proveedor.php");
    }
?>