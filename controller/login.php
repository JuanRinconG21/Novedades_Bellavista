<?php
session_start();
if (isset($_POST['cedula']) && !empty($_POST['cedula']) && isset($_POST['pass']) && !empty($_POST['pass'])) {
    try {
    $cedula = $_POST['cedula'];
    $password =  md5($_POST['pass']);
    include('../model/MySQL.php');
    $conexion = new MySQL();
    $pdo = $conexion->conectar();
    $sql = "SELECT * FROM administradores WHERE idAdministradores=:idAdministradores AND pass=:pass";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idAdministradores', $cedula, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['cedula'] = $fila['idAdministradores'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['session'] = true;
        $_SESSION['estado'] = $fila['estado'];
        header("Location: ../view/inicio.php");
    } else {
        $_SESSION['error'] = "Usuario o Contraseña Incorrecta Intente Nuevamente";
        header("Location: ../index.php"); 
    }
    } catch (\Throwable $th) {
        echo $th;
    }
   
}else{
    echo "VACIOOO";
}
?>