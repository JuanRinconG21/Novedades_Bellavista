<?php
session_start();
if (isset($_POST['proveedor']) && !empty($_POST['proveedor'])) {
    try {
    $proveedor = $_POST['proveedor'];
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
}
?>