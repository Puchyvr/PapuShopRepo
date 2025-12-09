<?php

include 'Global/Session.php';
include 'Global/Config.php';
include 'Global/Conexion.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: Login.php');
    exit;
}

// Verificar que hay carrito
$carrito = $_SESSION['CARRITO'] ?? [];
if (empty($carrito)) {
    header('Location: VerCarrito.php?error=vacio');
    exit;
}

// Calcular total
$total = 0;
foreach ($carrito as $item) {
    $total += $item['PRECIO'] * $item['CANTIDAD'];
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Insertar venta
    $sqlVenta = "INSERT INTO ventas (IDusuario, Fecha, Total, Estado) VALUES (?, NOW(), ?, 'Completada')";
    $stmtVenta = $pdo->prepare($sqlVenta);
    $stmtVenta->execute([$_SESSION['usuario_id'], $total]);
    $idVenta = $pdo->lastInsertId();

    // Insertar items de la venta
    $sqlItem = "INSERT INTO ventas_items (IDventa, IDproducto, Cantidad, PrecioUnitario, Subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmtItem = $pdo->prepare($sqlItem);

    foreach ($carrito as $item) {
        $subtotal = $item['PRECIO'] * $item['CANTIDAD'];
        $stmtItem->execute([
            $idVenta,
            $item['ID'],
            $item['CANTIDAD'],
            $item['PRECIO'],
            $subtotal
        ]);
    }

    // Confirmar transacción
    $pdo->commit();

    // Vaciar carrito
    unset($_SESSION['CARRITO']);

    // Redirigir con éxito
    header('Location: ConfirmacionCompra.php?id=' . $idVenta);
    exit;

} catch (PDOException $e) {
    // Revertir transacción en caso de error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Redirigir con error
    error_log('Error al procesar compra: ' . $e->getMessage());
    header('Location: VerCarrito.php?error=procesamiento');
    exit;
}

?>
