<?php
session_start();
if (isset($_POST['cedula']) && !empty($_POST['cedula']) &&
isset($_POST['nombre']) && !empty($_POST['nombre'])
&&
isset($_POST['apellido']) && !empty($_POST['apellido'])
&&
isset($_POST['telefono']) && !empty($_POST['telefono'])
&&
isset($_POST['direccion']) && !empty($_POST['direccion'])) {
    try {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $cargo = $_POST['idCargo'];
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "UPDATE personal_mantenimiento SET nombre = :nombre, apellido = :apellido, telefono = :telefono, direccion = :direccion, estado = 0, Especialidad_idEspecialidad	=:idCargo WHERE idPersonal_Mantenimiento = :cedula;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cedula', strtoupper($cedula), PDO::PARAM_STR);
    $stmt->bindParam(':nombre', strtoupper($nombre), PDO::PARAM_STR);
    $stmt->bindParam(':apellido', strtoupper($apellido), PDO::PARAM_STR);
    $stmt->bindParam(':telefono', strtoupper($telefono), PDO::PARAM_STR);
    $stmt->bindParam(':direccion', strtoupper($direccion), PDO::PARAM_STR);
    $stmt->bindParam(':idCargo', strtoupper($cargo), PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['felicitaciones'] = 'Empleado Editado Correctamente';
    header("Location: ../view/personal.php"); 
    } catch (\Throwable $th) {
        echo $th;
        $_SESSION['error'] = "Error al Editar Contacte a Soporte";
        header("Location: ../view/empleados.php"); 
    }
}else{
    $_SESSION['error'] = "Error al Editar No Deje Campos Vacios";
    header("Location: ../view/empleados.php");
}
?>