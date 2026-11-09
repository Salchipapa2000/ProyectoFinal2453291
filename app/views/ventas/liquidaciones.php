<?php
function escaparLiquidacion($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

$totalVendido = 0;
$totalPagar = 0;

foreach ($liquidaciones as $fila) {
    $totalVendido += (float)$fila['monto_vendido'];
    $totalPagar += (float)$fila['monto_pagar'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Liquidación mensual</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="contenedor">

    <h1>Liquidación mensual por artesano</h1>

    <form method="GET" action="index.php">
        <input type="hidden" name="accion" value="liquidaciones">

        <label>Mes:</label>
        <select name="mes">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>"
                    <?= $mes === $i ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                </option>
            <?php endfor; ?>
        </select>

        <label>Año:</label>
        <input type="number" name="anio" value="<?= $anio ?>" min="2000" max="2100">

        <button type="submit">Consultar</button>
    </form>

    <h3>Período: <?= escaparLiquidacion($mes) ?>/<?= escaparLiquidacion($anio) ?></h3>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Artesano</th>
                <th>Producto</th>
                <th>Unidades vendidas</th>
                <th>Monto vendido</th>
                <th>Monto a pagar (80 %)</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($liquidaciones)): ?>
                <tr>
                    <td colspan="5">No hay ventas registradas en este período.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($liquidaciones as $fila): ?>
                    <tr>
                        <td><?= escaparLiquidacion($fila['artesano']) ?></td>
                        <td><?= escaparLiquidacion($fila['producto']) ?></td>
                        <td><?= (int)$fila['unidades_vendidas'] ?></td>
                        <td>Q<?= number_format($fila['monto_vendido'], 2) ?></td>
                        <td>Q<?= number_format($fila['monto_pagar'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3">TOTALES</th>
                <th>Q<?= number_format($totalVendido, 2) ?></th>
                <th>Q<?= number_format($totalPagar, 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <p><a href="index.php?accion=ventas">Volver a ventas</a></p>
    <p><a href="index.php?accion=listar">Volver a productos</a></p>

</div>

</body>
</html>
