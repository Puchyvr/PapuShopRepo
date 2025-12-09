<?php

include 'Global/Session.php';
include 'Global/Config.php';
include 'Global/Conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($usuario) || empty($password)) {
        $error = 'Por favor completa todos los campos.';
    } else {
        try {
            $sql = "SELECT u.ID, u.NombreUsuario, u.Password, r.NombreRol, r.ID AS IDrol
                    FROM usuarios u
                    INNER JOIN roles r ON u.IDrol = r.ID
                    WHERE u.NombreUsuario = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario]);
            $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuarioData && password_verify($password, $usuarioData['Password'])) {
                $_SESSION['usuario_id'] = $usuarioData['ID'];
                $_SESSION['usuario_nombre'] = $usuarioData['NombreUsuario'];
                $_SESSION['rol'] = $usuarioData['NombreRol'];
                $_SESSION['rol_id'] = $usuarioData['IDrol'];

                if ($usuarioData['NombreRol'] == 'Administrador') {
                    header('Location: Dashboard.php');
                } else {
                    header('Location: Tienda.php');
                }
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos.';
            }
        } catch (PDOException $e) {
            $error = 'Error en la base de datos: ' . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PapuShop</title>
    <link rel="stylesheet" href="Assets/Css/Style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1 class="login-titulo">Iniciar Sesión</h1>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="Login.php" class="login-form">
                <div class="form-group">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </form>

            <p class="login-footer">¿No tienes cuenta? <a href="Registro.php" class="link-registro">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>
