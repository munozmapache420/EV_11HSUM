<?php

class Database
{
    public function getConnection()
    {
        $archivoEnv = __DIR__ . '/.env';
        $config = is_file($archivoEnv) ? parse_ini_file($archivoEnv) : [];

        $host = $config['DB_HOST'] ?? 'localhost';
        $port = $config['DB_PORT'] ?? '3306';
        $nombreBD = $config['DB_NAME'] ?? 'moonshine_v2';
        $usuario = $config['DB_USER'] ?? 'root';
        $clave = $config['DB_PASSWORD'] ?? '';

        try {
            return new PDO(
                "mysql:host={$host};port={$port};dbname={$nombreBD};charset=utf8mb4",
                $usuario,
                $clave,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log('Moonshine DB: ' . $e->getMessage());
            throw new RuntimeException(
                'No se pudo conectar con la base de datos moonshine_v2. Verifica que MySQL esté iniciado y que config/.env tenga los datos correctos.',
                0,
                $e
            );
        }
    }
}
