<?php
header('Content-Type: application/json');
require_once 'db.php';

$slug = $_GET['categoria'] ?? null;

if (!$slug) {
    http_response_code(400);
    echo json_encode(['error' => 'Categoría requerida']);
    exit;
}

$sql = "
    SELECT 
        c.id,
        c.numero AS id_interno,
        c.titulo AS title
    FROM cantos c
    INNER JOIN categorias cat ON c.categoria_id = cat.id
    WHERE cat.slug = :slug
    AND c.activo = 1
    ORDER BY c.numero ASC
";

$stmt = $conexion->prepare($sql);
$stmt->execute(['slug' => $slug]);

$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado);
