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
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $cargo = $_POST['idCargo'];
     include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "SELECT * FROM usuarios WHERE idUsuarios=:idUsuarios AND estado = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idUsuarios', strtoupper($cedula), PDO::PARAM_STR);
    $stmt->execute();
    echo $stmt->rowCount();
     if($stmt->rowCount() > 0){
        $_SESSION['error'] = "Ya Hay un Empleado con esa Cedula";
        header("Location: ../view/empleados.php");
    }else{
        $sql = "SELECT * FROM usuarios WHERE idUsuarios=:idUsuarios AND estado = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idUsuarios', strtoupper($cedula), PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount()>0){
            $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, telefono = :telefono, estado = 0, Cargos_idCargos=:idCargo WHERE idUsuarios = :cedula;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cedula', strtoupper($cedula), PDO::PARAM_STR);
            $stmt->bindParam(':nombre', strtoupper($nombre), PDO::PARAM_STR);
            $stmt->bindParam(':apellido', strtoupper($apellido), PDO::PARAM_STR);
            $stmt->bindParam(':telefono', strtoupper($telefono), PDO::PARAM_STR);
            $stmt->bindParam(':idCargo', strtoupper($cargo), PDO::PARAM_STR);
            $stmt->execute();
            $_SESSION['felicitaciones'] = 'Empleado Insertado Correctamente';
            header("Location: ../view/empleados.php"); 
        }else{
            $sql = "INSERT INTO usuarios (idUsuarios, nombre, apellido, telefono, estado, Cargos_idCargos) VALUES (:cedula, :nombre, :apellido, :telefono, 0, :idCargo);";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cedula', strtoupper($cedula), PDO::PARAM_STR);
            $stmt->bindParam(':nombre', strtoupper($nombre), PDO::PARAM_STR);
            $stmt->bindParam(':apellido', strtoupper($apellido), PDO::PARAM_STR);
            $stmt->bindParam(':telefono', strtoupper($telefono), PDO::PARAM_STR);
            $stmt->bindParam(':idCargo', strtoupper($cargo), PDO::PARAM_STR);
            $stmt->execute();
            $_SESSION['felicitaciones'] = 'Empleado Insertado Correctamente';
            header("Location: ../view/empleados.php"); 
        }
    } 
    } catch (\Throwable $th) {
        echo $th;
        $_SESSION['error'] = "Error al Insertar Contacte a Soporte";
        header("Location: ../view/empleados.php"); 
    }
}else{
    echo "Vacio";
}
?>