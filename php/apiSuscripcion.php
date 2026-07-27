<?php
//15b — php/apiSuscripcion.php (recibe el POST y guarda):

header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Correo inválido']);
    exit;
}

try {
    $stmt = $conexion->prepare("INSERT INTO blog_suscriptores (email, confirmado) VALUES (:email, 0)");
    $stmt->execute(['email' => $email]);
    echo json_encode(['success' => true, 'mensaje' => 'Te has suscrito correctamente.']);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // email duplicado (UNIQUE)
        echo json_encode(['success' => true, 'mensaje' => 'Ya estabas suscrito.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al suscribirte']);
    }
}