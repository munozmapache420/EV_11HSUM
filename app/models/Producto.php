<?php

require_once __DIR__ . '/../../config/database.php';

class Producto
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT id, nombre, descripcion, categoria_id, id_inventario,
                       stock_minimo, estado, precio_compra, precio_venta, stock
                FROM producto
                ORDER BY id DESC";

        $consulta = $this->conn->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT id, nombre, descripcion, categoria_id, id_inventario,
                       stock_minimo, estado, precio_compra, precio_venta, stock
                FROM producto
                WHERE id = :id
                LIMIT 1";

        $consulta = $this->conn->prepare($sql);
        $consulta->execute([':id' => $id]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($datos)
    {
        $sql = "INSERT INTO producto
                    (nombre, descripcion, categoria_id, id_inventario,
                     precio_compra, precio_venta, stock, stock_minimo, estado)
                VALUES
                    (:nombre, :descripcion, :categoria_id, :id_inventario,
                     :precio_compra, :precio_venta, :stock, :stock_minimo, :estado)";

        $consulta = $this->conn->prepare($sql);

        return $consulta->execute([
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'],
            ':categoria_id' => $datos['categoria_id'],
            ':id_inventario' => $datos['id_inventario'],
            ':precio_compra' => $datos['precio_compra'],
            ':precio_venta' => $datos['precio_venta'],
            ':stock' => $datos['stock'],
            ':stock_minimo' => $datos['stock_minimo'],
            ':estado' => $datos['estado']
        ]);
    }

    public function actualizar($id, $datos)
    {
        $sql = "UPDATE producto SET
                    nombre = :nombre,
                    descripcion = :descripcion,
                    categoria_id = :categoria_id,
                    id_inventario = :id_inventario,
                    precio_compra = :precio_compra,
                    precio_venta = :precio_venta,
                    stock = :stock,
                    stock_minimo = :stock_minimo,
                    estado = :estado
                WHERE id = :id";

        $consulta = $this->conn->prepare($sql);

        return $consulta->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'],
            ':categoria_id' => $datos['categoria_id'],
            ':id_inventario' => $datos['id_inventario'],
            ':precio_compra' => $datos['precio_compra'],
            ':precio_venta' => $datos['precio_venta'],
            ':stock' => $datos['stock'],
            ':stock_minimo' => $datos['stock_minimo'],
            ':estado' => $datos['estado']
        ]);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM producto WHERE id = :id";
        $consulta = $this->conn->prepare($sql);

        return $consulta->execute([':id' => $id]);
    }
}
