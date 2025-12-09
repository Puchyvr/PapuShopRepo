<?php
include 'Global/Session.php';

// Solo administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: Login.php');
    exit;
}

include 'Global/Config.php';
include 'Global/Conexion.php';

$error = '';

// Obtener categorias y marcas
$categorias = [];
$marcas = [];
try{
    $stmt = $pdo->query("SELECT ID, NombreCategoria FROM categorias ORDER BY NombreCategoria");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->query("SELECT ID, NombreMarca FROM marcas ORDER BY NombreMarca");
    $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e){
    die('Error BD: '.$e->getMessage());
}

// Obtener lista de imágenes desde Assets/Img
function listarImagenes()
{
    $base = __DIR__ . DIRECTORY_SEPARATOR . 'Assets' . DIRECTORY_SEPARATOR . 'Img';
    $list = [];
    if (is_dir($base)){
        $dirs = scandir($base);
        foreach ($dirs as $d){
            if ($d === '.' || $d === '..') continue;
            $sub = $base . DIRECTORY_SEPARATOR . $d;
            if (is_dir($sub)){
                $files = scandir($sub);
                foreach ($files as $f){
                    if ($f === '.' || $f === '..') continue;
                    $rel = 'Assets/Img/' . $d . '/' . $f;
                    $list[] = $rel;
                }
            }
        }
    }
    return $list;
}

$imagenes = listarImagenes();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = trim($_POST['precio'] ?? '0');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $imagen = trim($_POST['imagen'] ?? '');
    $idmarca = intval($_POST['idmarca'] ?? 0);
    $idcategoria = intval($_POST['idcategoria'] ?? 0);

    if ($nombre === '' || $precio === '' || $idmarca <= 0 || $idcategoria <= 0){
        $error = 'Completa nombre, precio, marca y categoría.';
    } else {
        try{
            $sql = "INSERT INTO productos (NombreProducto, Precio, DescripcionProducto, Imagen, IDmarca, IDcategoria)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $precio, $descripcion, $imagen, $idmarca, $idcategoria]);
            header('Location: GestionProductos.php?message=' . urlencode('Producto creado correctamente'));
            exit;
        } catch (PDOException $e){
            $error = 'Error al guardar: ' . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Crear Producto - PapuShop</title>
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

    <div class="form-container">
        <div class="form-header">
            <h1 class="form-titulo">Crear Nuevo Producto</h1>
            <a href="GestionProductos.php" class="btn btn-secondary">Volver</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="CrearProducto.php" class="product-form">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre del Producto *</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Sweater Azul" required>
                </div>

                <div class="form-group">
                    <label for="precio" class="form-label">Precio *</label>
                    <input type="number" id="precio" name="precio" step="0.01" class="form-input" placeholder="0.00" required>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-textarea" placeholder="Descripción del producto..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="idcategoria" class="form-label">Categoría *</label>
                    <select id="idcategoria" name="idcategoria" class="form-select" required>
                        <option value="">-- Seleccionar Categoría --</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?php echo $c['ID']; ?>"><?php echo htmlspecialchars($c['NombreCategoria']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="idmarca" class="form-label">Marca *</label>
                    <select id="idmarca" name="idmarca" class="form-select" required>
                        <option value="">-- Seleccionar Marca --</option>
                        <?php foreach ($marcas as $m): ?>
                            <option value="<?php echo $m['ID']; ?>"><?php echo htmlspecialchars($m['NombreMarca']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="imagen" class="form-label">Imagen</label>
                <select id="imagen" name="imagen" class="form-select">
                    <option value="">-- Sin Imagen --</option>
                    <?php foreach ($imagenes as $img): ?>
                        <option value="<?php echo $img; ?>"><?php echo basename($img); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-acciones">
                <button type="submit" class="btn btn-primary">Crear Producto</button>
                <a href="GestionProductos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <?php include 'Templates/Footer.php'; ?>
</body>
</html>