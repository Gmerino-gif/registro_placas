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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo "Correo electrónico no válido.";
        exit;
    }

    // Verificar si el correo existe en la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generar un token único para la recuperación de contraseña
        $token = bin2hex(random_bytes(16));
        $stmt = $conn->prepare("UPDATE usuarios SET reset_token = :token, token_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Enviar el correo electrónico con el enlace de recuperación
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        $subject = "Recuperación de contraseña";
        $message = "Hola,\n\nHaz clic en el siguiente enlace para restablecer tu contraseña:\n$resetLink\n\nEste enlace expirará en 1 hora.";
        $headers = "From: no-reply@yourwebsite.com\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "Se ha enviado un enlace de recuperación a tu correo electrónico.";
        } else {
            echo "No se pudo enviar el correo electrónico. Intenta más tarde.";
        }
    } else {
        echo "No se encontró una cuenta asociada a este correo electrónico.";
    }
}
?>
