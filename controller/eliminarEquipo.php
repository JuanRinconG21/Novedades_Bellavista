<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        include('../model/MySQL.php');
        $conexion = new MySQL();
        $pdo = $conexion->conectar();
        $sql = "UPDATE especialidad SET estadoBorrado=1 WHERE idEquipos=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        // Devolver una respuesta (puedes ajustar esto según tus necesidades)
        echo json_encode(['success' => true]);
    } catch (\Throwable $th) {
        echo json_encode(['error' => 'Método de solicitud no válido']);
    }
   
} else {
    // Manejar solicitudes no válidas
    echo json_encode(['error' => 'Método de solicitud no válido']);
}
?>