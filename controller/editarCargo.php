<?php
session_start();
if (isset($_POST['cargo']) && !empty($_POST['cargo'])) {
    try {
    $cargo = $_POST['cargo'];
    $idCargo = $_POST['idCargo'];
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "UPDATE cargos SET descripcion=:cargo WHERE idCargos=:idCargo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cargo', strtoupper($cargo), PDO::PARAM_STR);
    $stmt->bindParam(':idCargo',$idCargo, PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['felicitaciones'] = 'Cargo Editado Correctamente';
    header("Location: ../view/cargos.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Error al Editar Contacte a Soporte";
        header("Location: ../view/cargos.php");
    }
}
?>