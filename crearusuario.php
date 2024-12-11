<?php
// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'mi_sitio_web';
$user = 'root'; // Cambiar según tu configuración
$password = ''; // Cambiar según tu configuración

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar si el token es válido y no ha expirado
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE reset_token = :token AND token_expiration > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Mostrar formulario para restablecer contraseña
        echo '<form method="POST" action="reset_password.php">
                <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                <label for="password">Nueva Contraseña</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">Restablecer Contraseña</button>
              </form>';
    } else {
        echo "El enlace de recuperación es inválido o ha expirado.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token']) && isset($_POST['password'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Verificar el token nuevamente
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE reset_token = :token AND token_expiration > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Actualizar la contraseña y eliminar el token
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET password = :password, reset_token = NULL, token_expiration = NULL WHERE reset_token = :token");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':token', $token);

        if ($stmt->execute()) {
            echo "Tu contraseña ha sido restablecida con éxito.";
        } else {
            echo "Hubo un problema al restablecer la contraseña.";
        }
    } else {
        echo "El enlace de recuperación es inválido o ha expirado.";
    }
}
?>