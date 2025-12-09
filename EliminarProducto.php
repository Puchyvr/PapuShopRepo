<?php
include 'Global/Session.php';

// Solo administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: Login.php');
    exit;
}

include 'Global/Config.php';
include 'Global/Conexion.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: GestionProductos.php');
    exit;
}

try{
    // Iniciar transacción para garantizar consistencia
    $pdo->beginTransaction();
    
    // Primero eliminar los items de ventas asociados a este producto
    $stmtItems = $pdo->prepare("DELETE FROM ventas_items WHERE IDproducto = ?");
    $stmtItems->execute([$id]);
    
    // Luego eliminar el producto
    $stmtProducto = $pdo->prepare("DELETE FROM productos WHERE ID = ?");
    $stmtProducto->execute([$id]);
    
    // Confirmar transacción
    $pdo->commit();
    
    header('Location: GestionProductos.php?message=' . urlencode('Producto eliminado'));
    exit;
} catch (PDOException $e){
    // Revertir transacción en caso de error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die('Error al eliminar: ' . $e->getMessage());
}

?>