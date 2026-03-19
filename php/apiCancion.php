<?php
header('Content-Type: application/json');
require_once 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID requerido']);
    exit;
}

$sql = "
    SELECT 
        cantos.titulo,
        cantos.letra,
        cantos.bg_img AS imagen_path,
        categorias.slug AS categoria_slug
    FROM cantos
    INNER JOIN categorias ON cantos.categoria_id = categorias.id
    WHERE cantos.id = :id
    AND cantos.activo = 1
    LIMIT 1
";



$stmt = $conexion->prepare($sql);
$stmt->execute(['id' => $id]);

$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resultado) {
    http_response_code(404);
    echo json_encode(['error' => 'Canto no encontrado']);
    exit;
}

echo json_encode($resultado);
