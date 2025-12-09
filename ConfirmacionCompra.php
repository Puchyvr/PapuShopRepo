<?php

include 'Global/Session.php';
include 'Global/Config.php';
include 'Global/Conexion.php';
include 'Templates/Header.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: Login.php');
    exit;
}

$idVenta = intval($_GET['id'] ?? 0);

if ($idVenta <= 0) {
    header('Location: Tienda.php');
    exit;
}

// Obtener datos de la venta
try {
    $sqlVenta = "SELECT v.ID, v.Fecha, v.Total, v.Estado, u.NombreUsuario
                 FROM ventas v
                 INNER JOIN usuarios u ON v.IDusuario = u.ID
                 WHERE v.ID = ? AND v.IDusuario = ?";
    $stmtVenta = $pdo->prepare($sqlVenta);
    $stmtVenta->execute([$idVenta, $_SESSION['usuario_id']]);
    $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        header('Location: Tienda.php');
        exit;
    }

    // Obtener items de la venta
    $sqlItems = "SELECT vi.ID, vi.Cantidad, vi.PrecioUnitario, vi.Subtotal, p.NombreProducto
                 FROM ventas_items vi
                 INNER JOIN productos p ON vi.IDproducto = p.ID
                 WHERE vi.IDventa = ?";
    $stmtItems = $pdo->prepare($sqlItems);
    $stmtItems->execute([$idVenta]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Error al obtener datos de la venta: ' . $e->getMessage());
}

?>

<div class="confirmacion-container">
    <div class="confirmacion-box">
        <h1 class="confirmacion-titulo">¡Compra Realizada!</h1>
        
        <div class="confirmacion-mensaje">
            <p>Tu compra ha sido procesada exitosamente.</p>
            <p><strong>Número de venta:</strong> #<?php echo $venta['ID']; ?></p>
        </div>

        <div class="confirmacion-detalles">
            <h2 class="detalles-titulo">Detalles de tu compra</h2>
            
            <div class="venta-info">
                <p><strong>Cliente:</strong> <?php echo htmlspecialchars($venta['NombreUsuario']); ?></p>
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($venta['Fecha'])); ?></p>
                <p><strong>Estado:</strong> <span class="estado-completada"><?php echo htmlspecialchars($venta['Estado']); ?></span></p>
            </div>

            <table border="1" cellpadding="6" cellspacing="0" class="confirmacion-tabla">
                <thead>
                    <tr class="tabla-encabezado">
                        <th class="tabla-header">Producto</th>
                        <th class="tabla-header">Cantidad</th>
                        <th class="tabla-header">Precio unitario</th>
                        <th class="tabla-header">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr class="tabla-fila">
                            <td class="tabla-dato"><?php echo htmlspecialchars($item['NombreProducto']); ?></td>
                            <td class="tabla-dato"><?php echo (int)$item['Cantidad']; ?></td>
                            <td class="tabla-dato"><?php echo '$' . number_format($item['PrecioUnitario'], 2, ',', '.'); ?></td>
                            <td class="tabla-dato"><?php echo '$' . number_format($item['Subtotal'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="confirmacion-total">
                <p class="total-label"><strong>Total de la compra: <?php echo '$' . number_format($venta['Total'], 2, ',', '.'); ?></strong></p>
            </div>
        </div>

        <div class="confirmacion-acciones">
            <a href="Tienda.php" class="btn btn-primary">Seguir comprando</a>
            <a href="Index.php" class="btn btn-secondary">Ir al inicio</a>
        </div>
    </div>
</div>

<?php include 'Templates/Footer.php'; ?>
