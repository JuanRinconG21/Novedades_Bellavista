<?php
session_start();
if (isset($_POST['cargo']) && !empty($_POST['cargo'])) {
    try {
    $cargo = $_POST['cargo'];
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "INSERT INTO cargos (descripcion,estado) VALUES (:cargo,0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cargo', strtoupper($cargo), PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['felicitaciones'] = 'Cargo Insertado Correctamente';
    header("Location: ../view/cargos.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Error al Insertar Contacte a Soporte";
        header("Location: ../view/cargos.php");
    }
}
?>