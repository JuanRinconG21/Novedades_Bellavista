<?php
session_start();
if (isset($_POST['nombre']) && !empty($_POST['nombre']) &&
isset($_POST['numeroSerie']) && !empty($_POST['numeroSerie'])
&&
isset($_POST['valor']) && !empty($_POST['valor']))
{
    try {
    $nombre = $_POST['nombre'];
    $numeroSerie = $_POST['numeroSerie'];
    $fechaAdquisicion = $_POST['fechaAdquisicion'];
    $fechaAdquisicion = date('Y-m-d', strtotime($fechaAdquisicion));
    $garantia = $_POST['garantia'];
    $ultimoMantenimiento = $_POST['ultimoMantenimiento'];
    $ultimoMantenimiento = date('Y-m-d', strtotime($ultimoMantenimiento));
    $valor =$_POST['valor'];
    $estado = $_POST['estado'];
    $usuario = $_POST['usuario'];
    $proveedor = $_POST['proveedor'];
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "SELECT * FROM equipos WHERE numeroSerie=:numeroSerie AND estadoBorrado = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':numeroSerie', strtoupper($numeroSerie), PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        $_SESSION['error'] = "Ya Existe ese Equipo con un Numero de Serie";
        header("Location: ../view/equipo.php");
    }else{
        $sql = "SELECT * FROM equipos WHERE numeroSerie=:numeroSerie AND estadoBorrado = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numeroSerie', strtoupper($numeroSerie), PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount()>0){
            $sql = "UPDATE equipos SET nombre = :nombre, numeroSerie = :numSerie, fechaAquisicion = :fechaAdqui, garantia = :garantia, ultimoMantenimiento = :fechaUltimo, valor = :precio, estado = :estado, Usuarios_idUsuarios = :usuario, Proveedor_idProveedor = :proveedor WHERE equipos.idEquipos = :idEquipo;";
            $stmt = $pdo->prepare($sql);
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', strtoupper($nombre), PDO::PARAM_STR);
            $stmt->bindParam(':numSerie', strtoupper($numeroSerie), PDO::PARAM_STR);
            $stmt->bindParam(':fechaAdqui', strtoupper($fechaAdquisicion), PDO::PARAM_STR);
            $stmt->bindParam(':garantia', strtoupper($garantia), PDO::PARAM_STR);
            $stmt->bindParam(':fechaUltimo', strtoupper($ultimoMantenimiento), PDO::PARAM_STR);
            $stmt->bindParam(':precio', strtoupper($valor), PDO::PARAM_STR);
            $stmt->bindParam(':estado', strtoupper($estado), PDO::PARAM_STR);
            $stmt->bindParam(':usuario', strtoupper($usuario), PDO::PARAM_STR);
            $stmt->bindParam(':proveedor', strtoupper($proveedor), PDO::PARAM_STR);
            $stmt->bindParam(':idEquipo', strtoupper($idEquipo), PDO::PARAM_STR);
            $stmt->execute();
            $_SESSION['felicitaciones'] = 'Empleado Insertado Correctamente';
            header("Location: ../view/equipos.php"); 
        }else{
            $sql = "INSERT INTO `equipos` (idEquipos, nombre, numeroSerie, fechaAquisicion, garantia, ultimoMantenimiento, valor, estado, Usuarios_idUsuarios, Proveedor_idProveedor, estadoBorrado) VALUES (NULL, :nombre, :numSerie, :fechaAdqui, :garantia, :fechaUltimo, :precio, :estado, :usuario, :proveedor, 0);";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', strtoupper($nombre), PDO::PARAM_STR);
            $stmt->bindParam(':numSerie', strtoupper($numeroSerie), PDO::PARAM_STR);
            $stmt->bindParam(':fechaAdqui', strtoupper($fechaAdquisicion), PDO::PARAM_STR);
            $stmt->bindParam(':garantia', strtoupper($garantia), PDO::PARAM_STR);
            $stmt->bindParam(':fechaUltimo', strtoupper($ultimoMantenimiento), PDO::PARAM_STR);
            $stmt->bindParam(':precio', $valor, PDO::PARAM_STR);
            $stmt->bindParam(':estado', strtoupper($estado), PDO::PARAM_STR);
            $stmt->bindParam(':usuario', strtoupper($usuario), PDO::PARAM_STR);
            $stmt->bindParam(':proveedor', strtoupper($proveedor), PDO::PARAM_STR);
            $stmt->execute();
            $_SESSION['felicitaciones'] = 'Empleado Insertado Correctamente';
            header("Location: ../view/equipos.php"); 
        }
    } 
    } catch (\Throwable $th) {
        echo $th;
        echo($nombre = $_POST['nombre']."</br></br>");
        echo( $numeroSerie = $_POST['numeroSerie']."</br></br>");
        echo( $fechaAdquisicion = $_POST['fechaAdquisicion']."</br></br>");
        echo( $fechaAdquisicion = date('Y-m-d', strtotime($fechaAdquisicion))."</br></br>");
        echo($garantia = $_POST['garantia']."</br></br>");
        echo($ultimoMantenimiento = $_POST['ultimoMantenimiento']."</br></br>");
        echo($ultimoMantenimiento = date('Y-m-d', strtotime($ultimoMantenimiento))."</br></br>");
        echo($valor =$_POST['valor']."</br></br>");
        echo ($estado = $_POST['estado']."</br></br>");
        echo($usuario = $_POST['usuario']."</br></br>");
        echo($proveedor = $_POST['proveedor']."</br></br>");
        //$_SESSION['error'] = "Error al Insertar Contacte a Soporte";
        //header("Location: ../view/personal.php");  
    }
}else{
    $_SESSION['error'] = "Error al Insertar No Deje Campos Vacios";
    header("Location: ../view/equipos.php"); 
}
?>