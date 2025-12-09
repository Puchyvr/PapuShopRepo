<?php

include 'Global/Session.php';
include 'Global/Config.php';
include 'Global/Conexion.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');

    if (empty($usuario) || empty($password) || empty($password_confirm)) {
        $error = 'Por favor completa todos los campos.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        try {
            // Verificar si el usuario ya existe
            $sqlCheck = "SELECT ID FROM usuarios WHERE NombreUsuario = ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$usuario]);
            
            if ($stmtCheck->fetch()) {
                $error = 'El usuario ya existe. Por favor elige otro nombre.';
            } else {
                // Hash de la contraseña
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insertar nuevo usuario con rol "Usuario" (ID = 2)
                $sql = "INSERT INTO usuarios (NombreUsuario, Password, IDrol) VALUES (?, ?, 2)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$usuario, $passwordHash]);
                
                $exito = 'Registro exitoso. Ahora puedes <a href="Login.php">iniciar sesión</a>.';
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
    <title>Registro - PapuShop</title>
    <link rel="stylesheet" href="Assets/Css/Style.css">
</head>
<body>
    <div class="registro-container">
        <div class="registro-box">
            <h1 class="registro-titulo">Registrarse</h1>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($exito)): ?>
                <div class="alert alert-success">
                    <p><?php echo $exito; ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="Registro.php" class="registro-form">
                <div class="form-group">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="password_confirm" class="form-label">Confirmar Contraseña:</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </form>

            <p class="registro-footer">¿Ya tienes cuenta? <a href="Login.php" class="link-login">Inicia sesión aquí</a></p>
        </div>
    </div>
</body>
</html>
