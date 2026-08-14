<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: blog-list.php');
    exit;
}

$categorias = $conexion->query("SELECT id, nombre FROM blog_categorias ORDER BY nombre")->fetchAll();
$mensaje = '';

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $titulo), '-'));
    $extracto = trim($_POST['extracto']);
    $contenido = $_POST['contenido'];
    $categoria_id = $_POST['categoria_id'];
    $estado = $_POST['estado'];
    $imagen_portada = trim($_POST['imagen_portada']);
    // Si no viene con http, le agregamos el dominio automáticamente
    if ($imagen_portada && !preg_match('/^https?:\/\//', $imagen_portada)) {
        $imagen_portada = 'https://casadedios.mx/' . ltrim($imagen_portada, '/');
    }
    // Si pasa de borrador a publicado y no tenía fecha, se la asignamos ahora
    $stmtCheck = $conexion->prepare("SELECT fecha_publicacion FROM blog_posts WHERE id = :id");
    $stmtCheck->execute(['id' => $id]);
    $fechaActual = $stmtCheck->fetchColumn();
    $fecha_publicacion = ($estado === 'publicado' && !$fechaActual) ? date('Y-m-d H:i:s') : $fechaActual;

    $sql = "UPDATE blog_posts SET
                titulo = :titulo, slug = :slug, extracto = :extracto, contenido = :contenido,
                imagen_portada = :imagen_portada, categoria_id = :categoria_id,
                estado = :estado, fecha_publicacion = :fecha_publicacion
            WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        'titulo' => $titulo,
        'slug' => $slug,
        'extracto' => $extracto,
        'contenido' => $contenido,
        'imagen_portada' => $imagen_portada ?: null,
        'categoria_id' => $categoria_id,
        'estado' => $estado,
        'fecha_publicacion' => $fecha_publicacion,
        'id' => $id
    ]);

    $mensaje = "Post actualizado correctamente.";
}

// Cargar datos actuales (después de guardar, para reflejar cambios)
$stmt = $conexion->prepare("SELECT * FROM blog_posts WHERE id = :id");
$stmt->execute(['id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: blog-list.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Post | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <style>
        body {
            font-family: sans-serif;
            max-width: 700px;
            margin: 2rem auto;
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 0.5rem;
        }

        #editor {
            height: 300px;
            background: #fff;
        }

        button {
            margin-top: 1.5rem;
            padding: 0.7rem 1.5rem;
        }

        .ql-audio::before {
            content: "🔊";
        }
    </style>
</head>

<body>
    <h1>Editar Post</h1>
    <p><a href="blog-list.php">← Volver al listado</a></p>
    <?php if ($mensaje): ?>
        <p style="color:green;"><?= $mensaje ?></p><?php endif; ?>

    <form method="POST" id="postForm">
        <label>Título</label>
        <input type="text" name="titulo" value="<?= htmlspecialchars($post['titulo']) ?>" required>

        <label>Extracto (máx. 200 caracteres)</label>
        <input type="text" name="extracto" maxlength="200" value="<?= htmlspecialchars($post['extracto'] ?? '') ?>">

        <label>Imagen de portada (URL)</label>
        <input type="text" name="imagen_portada" value="<?= htmlspecialchars($post['imagen_portada'] ?? '') ?>">

        <label>Categoría</label>
        <select name="categoria_id">
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $post['categoria_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Estado</label>
        <select name="estado">
            <option value="borrador" <?= $post['estado'] === 'borrador' ? 'selected' : '' ?>>Borrador</option>
            <option value="publicado" <?= $post['estado'] === 'publicado' ? 'selected' : '' ?>>Publicado</option>
        </select>

        <label>Contenido</label>
        <div id="editor"></div>
        <input type="hidden" name="contenido" id="contenidoInput">

        <button type="submit">Guardar Cambios</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        const toolbarOptions = [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link', 'image', 'audio'],
            ['clean']
        ];

        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: toolbarOptions,
                    handlers: {
                        image: function () {
                            const url = prompt('Pega la URL de la imagen (ya subida por FTP):');
                            if (url) {
                                const range = this.quill.getSelection(true);
                                this.quill.insertEmbed(range.index, 'image', url);
                            }
                        },
                        audio: function () {
                            const url = prompt('Pega la URL del archivo .mp3 (ya subido por FTP):');
                            if (url) {
                                const range = this.quill.getSelection(true);
                                const html = `<button class="shofar-play-btn" onclick="new Audio('${url}').play()"><i class="bi bi-play-circle-fill"></i> Escuchar</button>`;
                                this.quill.clipboard.dangerouslyPasteHTML(range.index, html);
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>