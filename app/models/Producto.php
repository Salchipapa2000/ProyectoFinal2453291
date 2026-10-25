<?php

class Producto
{
    private mysqli $conexion;

    public function __construct(mysqli $conexion)
    {
        $this->conexion = $conexion;
    }

    // Listar todos los productos junto con el nombre del artesano.
    public function listar(): array
    {
        $sql = "SELECT p.*, a.nombre AS artesano
                FROM productos p
                INNER JOIN artesanos a ON p.artesano_id = a.id
                ORDER BY p.id DESC";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener un producto específico.
    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM productos WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();

        $stmt->close();

        return $producto ?: null;
    }

    // Obtener los artesanos disponibles para los formularios.
    public function obtenerArtesanos(): array
    {
        $sql = "SELECT id, nombre FROM artesanos ORDER BY nombre";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Registrar un producto.
    public function crear(
        int $artesano_id,
        string $nombre,
        string $categoria,
        float $precio,
        int $existencia,
        int $existencia_minima
    ): bool {
        $sql = "INSERT INTO productos
                (artesano_id, nombre, categoria, precio,
                 existencia, existencia_minima)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "issdii",
            $artesano_id,
            $nombre,
            $categoria,
            $precio,
            $existencia,
            $existencia_minima
        );

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    // Actualizar un producto existente.
    public function actualizar(
        int $id,
        int $artesano_id,
        string $nombre,
        string $categoria,
        float $precio,
        int $existencia,
        int $existencia_minima
    ): bool {
        $sql = "UPDATE productos
                SET artesano_id = ?,
                    nombre = ?,
                    categoria = ?,
                    precio = ?,
                    existencia = ?,
                    existencia_minima = ?
                WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "issdiii",
            $artesano_id,
            $nombre,
            $categoria,
            $precio,
            $existencia,
            $existencia_minima,
            $id
        );

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    // Eliminar un producto.
    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM productos WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }
}