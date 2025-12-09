<?php
include 'Global/Session.php';

include 'Templates/Header.php';

$carrito = $_SESSION['CARRITO'] ?? [];

function calcularTotal($carrito){
    $total = 0;
    foreach($carrito as $item){
        $total += $item['PRECIO'] * $item['CANTIDAD'];
    }
    return $total;
}

?>

<h2 class="carrito-titulo">Lista del Carrito</h2>

<?php if (empty($carrito)): ?>
    <p class="carrito-vacio">El carrito está vacío.</p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0" class="carrito-tabla">
        <thead>
            <tr class="tabla-encabezado">
                <th class="tabla-header">Producto</th>
                <th class="tabla-header">Cantidad</th>
                <th class="tabla-header">Precio unitario</th>
                <th class="tabla-header">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($carrito as $item): ?>
                <tr class="tabla-fila">
                    <td class="tabla-dato"><?php echo htmlspecialchars($item['NOMBRE']); ?></td>
                    <td class="tabla-dato"><?php echo (int)$item['CANTIDAD']; ?></td>
                    <td class="tabla-dato"><?php echo '$' . number_format($item['PRECIO'],2,',','.'); ?></td>
                    <td class="tabla-dato"><?php echo '$' . number_format($item['PRECIO'] * $item['CANTIDAD'],2,',','.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="carrito-total">
        <p class="total-label"><strong>Total: <?php echo '$' . number_format(calcularTotal($carrito),2,',','.'); ?></strong></p>
    </div>

    <div class="carrito-acciones">
        <form method="post" action="ProcesarCompra.php" class="form-comprar">
            <button type="submit" class="btn btn-comprar">Proceder con la compra</button>
        </form>
        <form method="post" action="VaciarCarrito.php" class="form-vaciar">
            <button type="submit" class="btn btn-vaciar">Vaciar Carrito</button>
        </form>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php 
                $error = $_GET['error'];
                if ($error === 'vacio') echo 'El carrito está vacío.';
                elseif ($error === 'procesamiento') echo 'Hubo un error al procesar tu compra. Por favor intenta nuevamente.';
                else echo 'Error desconocido.';
            ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php include 'Templates/Footer.php'; ?>
