<?php
class MySQL
{
    private $ipServidor = "localhost";
    private $usuarioBase = 'root';
    private $databaseName = 'bd_mantenimiento';
    private $contrasena = '';
    private $conexion;
    private $resultadoConsulta;

    public function conectar()
    {
        try {
            $this->conexion = new PDO("mysql:host={$this->ipServidor};dbname={$this->databaseName}", $this->usuarioBase, $this->contrasena);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexion;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function desconectar()
    {
        $this->conexion = null;
    }

    public function efectuarConsulta($consulta)
    {
        $this->conexion->exec("SET lc_time_names = 'es_ES'");
        $this->conexion->exec("SET NAMES 'utf8'");

        try {
            $stmt = $this->conexion->query($consulta);
            $this->resultadoConsulta = $stmt;
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }

        return $this->resultadoConsulta;
    }
}