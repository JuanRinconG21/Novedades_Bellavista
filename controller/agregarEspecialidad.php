<?php
session_start();
if (isset($_POST['especialidad']) && !empty($_POST['especialidad'])) {
    try {
    $especialidad = $_POST['especialidad'];
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "INSERT INTO especialidad (descripcion,estado) VALUES (:cargo,0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cargo', strtoupper($especialidad), PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['felicitaciones'] = 'Especialidad Insertada Correctamente';
    header("Location: ../view/especialidad.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Error al Insertar Contacte a Soporte";
        header("Location: ../view/especialidad.php");
    }
}
?>