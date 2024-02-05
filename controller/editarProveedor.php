<?php
session_start();
if (isset($_POST['proveedor']) && !empty($_POST['proveedor'])) {
    try {
        $cargo = $_POST['proveedor'];
        $idProveedor = $_POST['idProveedor'];
        include('../model/MySQL.php');
        $conexion = new MySQL();
        $pdo = $conexion->conectar();
        $sql = "UPDATE proveedor SET nombre=:cargo WHERE idProveedor=:idProveedor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cargo', strtoupper($cargo), PDO::PARAM_STR);
        $stmt->bindParam(':idProveedor',$idProveedor, PDO::PARAM_STR);
        $stmt->execute();
    $_SESSION['felicitaciones'] = 'Proveedor Editado Correctamente';
    header("Location: ../view/proveedor.php");
    } catch (\Throwable $th) {
        echo $th;
    $_SESSION['error'] = "Error al Editar Contacte a Soporte";
    header("Location: ../view/proveedor.php");
    }
}
?>