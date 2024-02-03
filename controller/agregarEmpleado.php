<?php
session_start();
if (isset($_POST['cedula']) && !empty($_POST['cedula']) &&
isset($_POST['nombre']) && !empty($_POST['nombre'])
&&
isset($_POST['apellido']) && !empty($_POST['apellido'])
&&
isset($_POST['telefono']) && !empty($_POST['telefono'])
) {
    try {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['especialidad'];
    $telefono = $_POST['telefono'];
    $cargo = $_POST['idCargo'];
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "INSERT INTO usuarios (idUsuarios, nombre, apellido, telefono, Cargos_idCargos) VALUES (:cedula, :nombre, :apellido, :telefono, :idCargo);";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cedula', strtoupper($cedula), PDO::PARAM_STR);
    $stmt->bindParam(':nombre', strtoupper($nombre), PDO::PARAM_STR);
    $stmt->bindParam(':apellido', strtoupper($apellido), PDO::PARAM_STR);
    $stmt->bindParam(':telefono', strtoupper($telefono), PDO::PARAM_STR);
    $stmt->bindParam(':idCargo', strtoupper($cargo), PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['felicitaciones'] = 'Empleado Insertado Correctamente';
    header("Location: ../view/empleados.php");
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Error al Insertar Contacte a Soporte";
        header("Location: ../view/empleados.php");
    }
}
?>