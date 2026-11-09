<?php
function escapar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar venta</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="contenedor">
    <h1>Registrar venta</h1>

    <p>Usuario: <?= escapar($_SESSION['nombre']) ?></p>

    <p>
        <a href="index.php?accion=listar">Volver a productos</a> |
        <a href="index.php?accion=logout">Cerrar sesión</a>
    </p>

    <?php if (isset($_GET['exito'])): ?>
        <p class="mensaje-exito">¡Venta registrada correctamente!</p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <p class="mensaje-error">
            No se pudo registrar la venta. Verifica los productos y sus existencias.
        </p>
    <?php endif; ?>

    <form action="index.php?accion=registrar_venta" method="POST">

        <div id="productos-container">

            <div class="fila-producto">
                <select name="productos[0][id]" class="producto" required>
                    <option value="">Selecciona un producto</option>

                    <?php foreach ($productos as $producto): ?>
                        <option
                            value="<?= (int)$producto['id'] ?>"
                            data-precio="<?= escapar($producto['precio']) ?>"
                        >
                            <?= escapar($producto['nombre']) ?>
                            - Q<?= number_format($producto['precio'], 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input
                    type="number"
                    name="productos[0][cantidad]"
                    class="cantidad"
                    min="1"
                    placeholder="Cantidad"
                    required
                >

                <span class="alerta"></span>
            </div>

        </div>

        <button type="button" id="agregar">
            Agregar otro producto
        </button>

        <h3 id="total">Total: Q0.00</h3>

        <button type="submit">Registrar venta</button>

    </form>
</div>

<script>
const productosContainer = document.getElementById('productos-container');
let contador = 1;

function consultarExistencia(fila) {
    const select = fila.querySelector('.producto');
    const cantidad = fila.querySelector('.cantidad');
    const alerta = fila.querySelector('.alerta');

    const id = select.value;
    const unidades = cantidad.value;

    alerta.textContent = '';

    if (!id || !unidades || Number(unidades) < 1) {
        calcularTotal();
        return;
    }

    fetch(`index.php?accion=consultar_existencia&id=${id}&cantidad=${unidades}`)
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (datos.error) {
                alerta.textContent = datos.error;
                return;
            }

            if (Number(unidades) > datos.existencia) {
                alerta.textContent = '¡Existencia insuficiente!';
            } else if (datos.alerta) {
                alerta.textContent =
                    `Advertencia: quedarán ${datos.restante} unidades, por debajo del mínimo (${datos.minima}).`;
            } else {
                alerta.textContent =
                    `Existencias disponibles: ${datos.existencia}.`;
            }
        })
        .catch(() => {
            alerta.textContent = 'No se pudo consultar el inventario.';
        });

    calcularTotal();
}

function calcularTotal() {
    let total = 0;

    document.querySelectorAll('.fila-producto').forEach(fila => {
        const select = fila.querySelector('.producto');
        const cantidad = Number(fila.querySelector('.cantidad').value);
        const precio = Number(
            select.selectedOptions[0]?.dataset.precio || 0
        );

        total += precio * cantidad;
    });

    document.getElementById('total').textContent =
        'Total: Q' + total.toFixed(2);
}

function configurarFila(fila) {
    fila.querySelector('.producto').addEventListener('change', () => {
        consultarExistencia(fila);
    });

    fila.querySelector('.cantidad').addEventListener('input', () => {
        consultarExistencia(fila);
    });
}

configurarFila(document.querySelector('.fila-producto'));

document.getElementById('agregar').addEventListener('click', () => {
    const fila = document.createElement('div');

    fila.className = 'fila-producto';

    fila.innerHTML = `
        <select name="productos[${contador}][id]" class="producto" required>
            <option value="">Selecciona un producto</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= (int)$producto['id'] ?>"
                    data-precio="<?= escapar($producto['precio']) ?>">
                    <?= escapar($producto['nombre']) ?>
                    - Q<?= number_format($producto['precio'], 2) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="number"
            name="productos[${contador}][cantidad]"
            class="cantidad"
            min="1"
            placeholder="Cantidad"
            required>

        <span class="alerta"></span>

        <button type="button" class="quitar">Quitar</button>
    `;

    productosContainer.appendChild(fila);

    configurarFila(fila);

    fila.querySelector('.quitar').addEventListener('click', () => {
        fila.remove();
        calcularTotal();
    });

    contador++;
});
</script>

</body>
</html>
