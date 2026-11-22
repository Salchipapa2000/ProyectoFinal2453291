<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario | Manos de Chichi</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>Inventario de productos</h1>
        <p>Gestión de los productos artesanales de Manos de Chichi.</p>

        <a class="boton" href="index.php?accion=crear"> <i class="fa-solid fa-solidLarge fa-plus">‌</i>
            Registrar producto
        </a>


        <a class="boton" href="index.php?accion=inicio">
                <i class="fa-solid fa-house"></i> Ir al inicio
            </a>
        <a class="boton" href="index.php?accion=logout">
                <i class="fa-solid fa-solidLarge fa-sign-in">‌</i> Cerrar sesión
            </a>

        <?php if (empty($productos)): ?>
            <p>No hay productos registrados todavía.</p>
        <?php else: ?>
            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Artesano</th>
                            <th>Precio</th>
                            <th>Existencias</th>
                            <th>Existencia mínima</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= (int) $producto['id'] ?></td>
                                <td>
                                    <?= htmlspecialchars($producto['nombre']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($producto['categoria']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($producto['artesano']) ?>
                                </td>
                                <td>
                                    Q<?= number_format((float) $producto['precio'], 2) ?>
                                </td>
                                <td><?= (int) $producto['existencia'] ?></td>
                                <td><?= (int) $producto['existencia_minima'] ?></td>
                                <td class="acciones">
                                    <a href="index.php?accion=editar&id=<?= (int) $producto['id'] ?>">
                                        Editar
                                    </a>

                                    <form
                                        action="index.php?accion=eliminar"
                                        method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');"
                                    >
                                    <?= campoTokenCSRF() ?>
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $producto['id'] ?>"
                                        >

                                        <button type="submit" class="boton-eliminar">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>