<?php
// Headers CORS primero
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Manejar preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

require_once 'db.php';

$action = $_GET['action'] ?? '';

try {
    $db = $conexion;

    switch ($action) {
        case 'getCategorias':
            $stmt = $db->query("SELECT id, nombre, slug FROM categorias WHERE activa = 1 ORDER BY orden");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'getCantosPorCategoria':
            $slug = $_GET['slug'] ?? '';
            $stmt = $db->prepare("
                SELECT c.* FROM cantos c 
                JOIN categorias cat ON c.categoria_id = cat.id 
                WHERE cat.slug = ? AND c.activo = 1 
                ORDER BY c.numero
            ");
            $stmt->execute([$slug]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        default:
            echo json_encode(['error' => 'Acción no válida']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>