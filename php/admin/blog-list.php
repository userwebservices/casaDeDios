<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../db.php';

// Borrar post si viene ?delete=id
if (isset($_GET['delete'])) {
    $stmt = $conexion->prepare("DELETE FROM blog_posts WHERE id = :id");
    $stmt->execute(['id' => $_GET['delete']]);
    header('Location: blog-list.php');
    exit;
}

$sql = "SELECT p.id, p.titulo, p.slug, p.estado, p.fecha_publicacion, c.nombre AS categoria
        FROM blog_posts p
        LEFT JOIN blog_categorias c ON p.categoria_id = c.id
        ORDER BY p.created_at DESC";
$posts = $conexion->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Posts | Admin</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 900px;
            margin: 2rem auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 0.6rem;
            border-bottom: 1px solid #ddd;
        }

        .badge {
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .badge--publicado {
            background: #d4edda;
            color: #155724;
        }

        .badge--borrador {
            background: #fff3cd;
            color: #856404;
        }

        a.btn {
            margin-right: 0.5rem;
            text-decoration: none;
        }

        .btn--danger {
            color: #c00;
        }
    </style>
</head>

<body>
    <h1>Posts del Blog</h1>
    <p><a href="blog-create.php">+ Nuevo post</a></p>

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post['titulo']) ?></td>
                    <td><?= htmlspecialchars($post['categoria'] ?? '—') ?></td>
                    <td><span class="badge badge--<?= $post['estado'] ?>"><?= $post['estado'] ?></span></td>
                    <td><?= $post['fecha_publicacion'] ?? '—' ?></td>
                    <td>
                        <a class="btn" href="blog-edit.php?id=<?= $post['id'] ?>">Editar</a>
                        <a class="btn btn--danger" href="blog-list.php?delete=<?= $post['id'] ?>"
                            onclick="return confirm('¿Borrar este post?');">Borrar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>