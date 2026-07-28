<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

try {
    $sql = "SELECT 
                p.id,
                p.titulo,
                p.slug,
                p.extracto,
                p.imagen_portada,
                p.fecha_publicacion,
                c.nombre AS categoria
            FROM blog_posts p
            LEFT JOIN blog_categorias c ON p.categoria_id = c.id
            WHERE p.estado = 'publicado'
            ORDER BY p.fecha_publicacion DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $posts = $stmt->fetchAll();

    echo json_encode($posts);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los posts']);
}