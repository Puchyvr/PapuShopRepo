<?php
include 'Global/Session.php';

// Solo administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: Login.php');
    exit;
}

include 'Global/Config.php';
include 'Global/Conexion.php';

$message = $_GET['message'] ?? '';

try {
    $sql = "SELECT p.ID, p.NombreProducto, p.Precio, p.Imagen, c.NombreCategoria AS categoria, m.NombreMarca AS marca
            FROM productos p
            LEFT JOIN categorias c ON p.IDcategoria = c.ID
            LEFT JOIN marcas m ON p.IDmarca = m.ID
            ORDER BY p.NombreProducto";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error BD: ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="Assets/Css/Style.css">
</head>
<body>
    <div class="gestion-container">
        <h1 class="gestion-titulo">Gestión de Productos</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>

        <div class="gestion-acciones">
            <a href="Dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
            <a href="CrearProducto.php" class="btn btn-primary">Crear producto</a>
        </div>

        <div class="gestion-tabla-wrapper">
            <table class="gestion-tabla">
                <thead>
                    <tr class="tabla-encabezado">
                        <th class="tabla-header">ID</th>
                        <th class="tabla-header">Imagen</th>
                        <th class="tabla-header">Nombre</th>
                        <th class="tabla-header">Precio</th>
                        <th class="tabla-header">Categoría</th>
                        <th class="tabla-header">Marca</th>
                        <th class="tabla-header">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                        <tr class="tabla-fila">
                            <td class="tabla-dato tabla-dato-id"><?php echo $p['ID']; ?></td>
                            <td class="tabla-dato tabla-dato-imagen">
                                <?php if (!empty($p['Imagen'])): ?>
                                    <img src="<?php echo htmlspecialchars(preg_replace('#^\.\./#','',$p['Imagen'])); ?>" alt="<?php echo htmlspecialchars($p['NombreProducto']); ?>" class="gestion-img">
                                <?php endif; ?>
                            </td>
                            <td class="tabla-dato"><?php echo htmlspecialchars($p['NombreProducto']); ?></td>
                            <td class="tabla-dato tabla-dato-precio"><?php echo '$' . number_format($p['Precio'],2,',','.'); ?></td>
                            <td class="tabla-dato"><?php echo htmlspecialchars($p['categoria']); ?></td>
                            <td class="tabla-dato"><?php echo htmlspecialchars($p['marca']); ?></td>
                            <td class="tabla-dato gestion-actions">
                                <a href="EditarProducto.php?id=<?php echo $p['ID']; ?>" class="action-link action-edit">Editar</a>
                                <a href="EliminarProducto.php?id=<?php echo $p['ID']; ?>" class="action-link action-delete" onclick="return confirm('¿Eliminar este producto?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>