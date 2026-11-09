<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Manos de Chichi</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <main class="contenedor">

        <h1>Manos de Chichi</h1>
        <h2>Iniciar sesión</h2>

        <?php if ($error): ?>
            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="index.php?accion=login">

            <label for="usuario">Usuario:</label>
            <input
                type="text"
                id="usuario"
                name="usuario"
                required
                autocomplete="username"
            >

            <label for="password">Contraseña:</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
            >

            <button type="submit">Ingresar</button>

        </form>

    </main>

</body>
</html>
