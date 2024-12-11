<?php
// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'registro_vehicular';
$user = 'root'; // Cambiar según tu configuración
$password = ''; // Cambiar según tu configuración

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $placa = filter_input(INPUT_POST, 'placa', FILTER_SANITIZE_STRING);
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);

    if (!$placa || !$tipo) {
        echo json_encode(["error" => "Faltan datos para registrar."]);
        exit;
    }

    $fechaHora = date('Y-m-d H:i:s');

    // Insertar el registro en la base de datos
    $stmt = $conn->prepare("INSERT INTO registros (placa, tipo, fecha_hora) VALUES (:placa, :tipo, :fecha_hora)");
    $stmt->bindParam(':placa', $placa);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':fecha_hora', $fechaHora);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Registro exitoso."]);
    } else {
        echo json_encode(["error" => "Hubo un problema al registrar los datos."]);
    }
}
?>