<?php
session_start();
if (isset($_POST['especialidad']) && !empty($_POST['especialidad'])) {
    try {
    $especialidad = $_POST['especialidad'];
    $idEspecialidad = $_POST['idEspecialidad'];
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "UPDATE especialidad SET descripcion=:especialidad WHERE idEspecialidad=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':especialidad', strtoupper($especialidad), PDO::PARAM_STR);
    $stmt->bindParam(':id', $idEspecialidad, PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['felicitaciones'] = 'Especialidad Editada Correctamente';
    header("Location: ../view/especialidad.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Error al Editar Contacte a Soporte";
        header("Location: ../view/especialidad.php");
    }
}
?>