<?php
session_start();
if (isset($_POST['proveedor']) && !empty($_POST['proveedor'])) {
    try {
    $proveedor = $_POST['proveedor'];
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "UPDATE proveedor SET nombre=:proveedor WHERE ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':proveedor', strtoupper($proveedor), PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['felicitaciones'] = 'Proveedor Editado Correctamente';
    header("Location: ../view/proveedor.php");
    } catch (\Throwable $th) {
        echo $th;
       // $_SESSION['error'] = "Error al Editar Contacte a Soporte";
        //header("Location: ../view/proveedor.php");
    }
}
?>