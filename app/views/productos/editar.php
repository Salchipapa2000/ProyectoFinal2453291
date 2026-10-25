<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto | Manos de Chichi</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>Editar producto</h1>

        <?php if ($error): ?>
            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form
            method="POST"
            action="index.php?accion=editar&id=<?= (int) $producto['id'] ?>"
        >
            <label for="artesano_id">Artesano</label>
            <select name="artesano_id" id="artesano_id" required>
                <?php foreach ($artesanos as $artesano): ?>
                    <option
                        value="<?= (int) $artesano['id'] ?>"
                        <?= (string) ($_POST['artesano_id'] ?? $producto['artesano_id']) === (string) $artesano['id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($artesano['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nombre">Nombre del producto</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                maxlength="100"
                value="<?= htmlspecialchars($_POST['nombre'] ?? $producto['nombre']) ?>"
                required
            >

            <label for="categoria">Categoría</label>
            <input
                type="text"
                id="categoria"
                name="categoria"
                maxlength="50"
                value="<?= htmlspecialchars($_POST['categoria'] ?? $producto['categoria']) ?>"
                required
            >

            <label for="precio">Precio (Q)</label>
            <input
                type="number"
                id="precio"
                name="precio"
                min="0.01"
                step="0.01"
                value="<?= htmlspecialchars($_POST['precio'] ?? $producto['precio']) ?>"
                required
            >

            <label for="existencia">Existencias</label>
            <input
                type="number"
                id="existencia"
                name="existencia"
                min="0"
                step="1"
                value="<?= htmlspecialchars($_POST['existencia'] ?? (string) $producto['existencia']) ?>"
                required
            >

            <label for="existencia_minima">Existencia mínima</label>
            <input
                type="number"
                id="existencia_minima"
                name="existencia_minima"
                min="0"
                step="1"
                value="<?= htmlspecialchars($_POST['existencia_minima'] ?? (string) $producto['existencia_minima']) ?>"
                required
            >

            <button type="submit" class="boton">Guardar cambios</button>
        </form>

        <a class="enlace-volver" href="index.php?accion=listar">
            Cancelar y volver
        </a>
    </main>
</body>
</html>