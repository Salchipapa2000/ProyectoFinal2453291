<?php

class Venta
{
    private mysqli $conexion;

    public function __construct(mysqli $conexion)
    {
        $this->conexion = $conexion;
    }

    // Registrar una venta y descontar existencias.
    public function registrar(int $usuario_id, array $productos): bool
    {
        $this->conexion->begin_transaction();

        try {
            $total = 0;
            $detalles = [];

            foreach ($productos as $producto) {
                $id = (int) $producto['id'];
                $cantidad = (int) $producto['cantidad'];

                if ($id <= 0 || $cantidad <= 0) {
                    throw new Exception("Producto o cantidad inválida.");
                }

                $stmt = $this->conexion->prepare(
                    "SELECT id, nombre, precio, existencia
                     FROM productos
                     WHERE id = ?
                     FOR UPDATE"
                );

                $stmt->bind_param("i", $id);
                $stmt->execute();

                $datos = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if (!$datos) {
                    throw new Exception("Producto no encontrado.");
                }

                if ($cantidad > (int) $datos['existencia']) {
                    throw new Exception(
                        "Existencia insuficiente para: " . $datos['nombre']
                    );
                }

                $subtotal = (float) $datos['precio'] * $cantidad;
                $total += $subtotal;

                $detalles[] = [
                    'id' => $id,
                    'cantidad' => $cantidad,
                    'precio' => $datos['precio'],
                    'subtotal' => $subtotal
                ];
            }

            $stmt = $this->conexion->prepare(
                "INSERT INTO ventas (usuario_id, total)
                 VALUES (?, ?)"
            );

            $stmt->bind_param("id", $usuario_id, $total);
            $stmt->execute();

            $venta_id = $this->conexion->insert_id;
            $stmt->close();

            foreach ($detalles as $detalle) {

                $stmt = $this->conexion->prepare(
                    "INSERT INTO detalle_ventas
                    (venta_id, producto_id, cantidad, precio_unitario, subtotal)
                    VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "iiidd",
                    $venta_id,
                    $detalle['id'],
                    $detalle['cantidad'],
                    $detalle['precio'],
                    $detalle['subtotal']
                );

                $stmt->execute();
                $stmt->close();

                $stmt = $this->conexion->prepare(
                    "UPDATE productos
                     SET existencia = existencia - ?
                     WHERE id = ? AND existencia >= ?"
                );

                $stmt->bind_param(
                    "iii",
                    $detalle['cantidad'],
                    $detalle['id'],
                    $detalle['cantidad']
                );

                $stmt->execute();

                if ($stmt->affected_rows !== 1) {
                    throw new Exception("No fue posible actualizar el inventario.");
                }

                $stmt->close();
            }

            $this->conexion->commit();

            return true;

        } catch (Throwable $e) {
            $this->conexion->rollback();
            throw $e;
        }
    }

    // Consultar productos disponibles.
    public function obtenerProductos(): array
    {
        $sql = "SELECT id, nombre, precio, existencia, existencia_minima
                FROM productos
                ORDER BY nombre";

        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}