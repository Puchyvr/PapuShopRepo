<?php

include 'Global/Session.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: Login.php');
    exit;
}

// Verificar si es administrador
if ($_SESSION['rol'] !== 'Administrador') {
    header('Location: Tienda.php');
    exit;
}

include 'Global/Config.php';
include 'Global/Conexion.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PapuShop</title>
    <link rel="stylesheet" href="Assets/Css/Style.css">
</head>
<body>
    <nav class="admin-nav">
        <ul class="admin-nav-list">
            <li class="admin-nav-item"><a href="Dashboard.php" class="admin-nav-link">Inicio</a></li>
            <li class="admin-nav-item"><a href="GestionProductos.php" class="admin-nav-link">Gestionar Productos</a></li>
            <li class="admin-nav-item"><a href="Logout.php" class="admin-nav-link logout-link">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <h1 class="dashboard-titulo">Panel de Administrador - PapuShop</h1>
        
        <div class="dashboard-welcome">
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h2>
            <p>Panel de control de administración de la tienda</p>
        </div>

        <!-- ESTADÍSTICAS -->
        <div class="dashboard-stats">
            <h2 class="stats-titulo">Estadísticas Generales</h2>
            <div class="stats-grid">
                <?php
                // Contar productos
                try {
                    $query_productos = "SELECT COUNT(*) as total FROM productos";
                    $stmt = $GLOBALS['pdo']->prepare($query_productos);
                    $stmt->execute();
                    $total_productos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                } catch (Exception $e) {
                    $total_productos = 0;
                }

                // Contar ventas
                try {
                    $query_ventas = "SELECT COUNT(*) as total FROM ventas";
                    $stmt = $GLOBALS['pdo']->prepare($query_ventas);
                    $stmt->execute();
                    $total_ventas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                } catch (Exception $e) {
                    $total_ventas = 0;
                }

                // Contar usuarios clientes (no administradores)
                try {
                    $query_usuarios = "SELECT COUNT(*) as total FROM usuarios WHERE IDrol = 2";
                    $stmt = $GLOBALS['pdo']->prepare($query_usuarios);
                    $stmt->execute();
                    $total_usuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                } catch (Exception $e) {
                    $total_usuarios = 0;
                }

                // Sumar ingresos
                try {
                    $query_ingresos = "SELECT SUM(Total) as ingresos FROM ventas";
                    $stmt = $GLOBALS['pdo']->prepare($query_ingresos);
                    $stmt->execute();
                    $ingresos = $stmt->fetch(PDO::FETCH_ASSOC)['ingresos'] ?? 0;
                    $ingresos = number_format($ingresos, 2, ',', '.');
                } catch (Exception $e) {
                    $ingresos = 0;
                }
                ?>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-productos">📦</div>
                    <div class="stat-info">
                        <h3 class="stat-valor"><?php echo $total_productos; ?></h3>
                        <p class="stat-label">Productos</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-ventas">💰</div>
                    <div class="stat-info">
                        <h3 class="stat-valor"><?php echo $total_ventas; ?></h3>
                        <p class="stat-label">Ventas Realizadas</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-usuarios">👥</div>
                    <div class="stat-info">
                        <h3 class="stat-valor"><?php echo $total_usuarios; ?></h3>
                        <p class="stat-label">Usuarios Clientes</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-ingresos">💸</div>
                    <div class="stat-info">
                        <h3 class="stat-valor">$<?php echo $ingresos; ?></h3>
                        <p class="stat-label">Ingresos Totales</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- OPCIONES RÁPIDAS -->
        <div class="dashboard-acciones">
            <h2 class="acciones-titulo">Acciones Rápidas</h2>
            <div class="dashboard-opciones">
                <a href="GestionProductos.php" class="opcion-card">
                    <div class="opcion-icon">📋</div>
                    <h3>Gestionar Productos</h3>
                    <p>Agregar, editar o eliminar productos del catálogo</p>
                </a>

                <a href="CrearProducto.php" class="opcion-card">
                    <div class="opcion-icon">➕</div>
                    <h3>Crear Producto</h3>
                    <p>Agregar un nuevo producto a la tienda</p>
                </a>

                <a href="Tienda.php" class="opcion-card">
                    <div class="opcion-icon">🛍️</div>
                    <h3>Ver Tienda</h3>
                    <p>Visualizar la tienda como cliente</p>
                </a>
            </div>
        </div>

        <!-- ÚLTIMAS VENTAS -->
        <div class="dashboard-ventas">
            <h2 class="ventas-titulo">Últimas Ventas</h2>
            <?php
            try {
                $query_ultimas = "SELECT v.ID as id_venta, v.Total as total, v.Fecha as fecha_venta, u.NombreUsuario as nombre_usuario 
                                 FROM ventas v 
                                 JOIN usuarios u ON v.IDusuario = u.ID 
                                 ORDER BY v.Fecha DESC 
                                 LIMIT 5";
                $stmt = $GLOBALS['pdo']->prepare($query_ultimas);
                $stmt->execute();
                $ultimas_ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($ultimas_ventas) > 0) {
                    echo '<div class="ventas-tabla-wrapper">';
                    echo '<table class="ventas-tabla">';
                    echo '<thead>';
                    echo '<tr class="tabla-header">';
                    echo '<th>ID Venta</th>';
                    echo '<th>Cliente</th>';
                    echo '<th>Total</th>';
                    echo '<th>Fecha</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    foreach ($ultimas_ventas as $venta) {
                        echo '<tr>';
                        echo '<td class="tabla-dato-id">#' . $venta['id_venta'] . '</td>';
                        echo '<td>' . htmlspecialchars($venta['nombre_usuario']) . '</td>';
                        echo '<td class="tabla-dato-precio">$' . number_format($venta['total'], 2, ',', '.') . '</td>';
                        echo '<td>' . date('d/m/Y H:i', strtotime($venta['fecha_venta'])) . '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-info">No hay ventas registradas aún</div>';
                }
            } catch (Exception $e) {
                echo '<div class="alert alert-error">Error al cargar las ventas: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>
    </div>

    <footer class="admin-footer">
        <p>&copy; 2024 PapuShop - Todos los derechos reservados | <a href="Logout.php" class="footer-logout-link">Cerrar Sesión</a></p>
    </footer>
</body>
</html>
