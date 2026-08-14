<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../mail/enviarNotificacion.php';

// Traer categorías para el select
$categorias = $conexion->query("SELECT id, nombre FROM blog_categorias ORDER BY nombre")->fetchAll();

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $titulo), '-'));
    $slug = trim($slug, '-');
    $extracto = trim($_POST['extracto']);
    $contenido = $_POST['contenido']; // viene del editor Quill
    $categoria_id = $_POST['categoria_id'];
    $estado = $_POST['estado'];
    $imagen_portada = trim($_POST['imagen_portada']);
    // Si no viene con http, le agregamos el dominio automáticamente
    if ($imagen_portada && !preg_match('/^https?:\/\//', $imagen_portada)) {
        $imagen_portada = 'https://casadedios.mx/' . ltrim($imagen_portada, '/');
    }

    $fecha_publicacion = ($estado === 'publicado') ? date('Y-m-d H:i:s') : null;

    $sql = "INSERT INTO blog_posts (titulo, slug, extracto, contenido, imagen_portada, categoria_id, estado, fecha_publicacion)
            VALUES (:titulo, :slug, :extracto, :contenido, :imagen_portada, :categoria_id, :estado, :fecha_publicacion)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        'titulo' => $titulo,
        'slug' => $slug,
        'extracto' => $extracto,
        'contenido' => $contenido,
        'imagen_portada' => $imagen_portada ?: null,
        'categoria_id' => $categoria_id,
        'estado' => $estado,
        'fecha_publicacion' => $fecha_publicacion
    ]);

    // Enviar notificación solo si se publicó
    if ($estado === 'publicado') {
        $nuevoId = $conexion->lastInsertId();
        $stmtPost = $conexion->prepare(
            "SELECT p.*, c.nombre AS categoria FROM blog_posts p
         LEFT JOIN blog_categorias c ON p.categoria_id = c.id
         WHERE p.id = :id"
        );
        $stmtPost->execute(['id' => $nuevoId]);
        $postCompleto = $stmtPost->fetch();
        enviarNotificacionPost($postCompleto, $conexion);
    }

    $mensaje = "Post guardado correctamente.";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo Post | Admin</title>
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
    <h1>Nuevo Post</h1>
    <?php if ($mensaje): ?>
        <p style="color:green;">
            <?= $mensaje ?>
        </p>
    <?php endif; ?>

    <form method="POST" id="postForm">
        <label>Título</label>
        <input type="text" name="titulo" required>

        <label>Extracto (máx. 200 caracteres)</label>
        <input type="text" name="extracto" maxlength="200">

        <label>Imagen de portada (URL)</label>
        <input type="text" name="imagen_portada" placeholder="assets/img/blog/mi-imagen.webp">

        <label>Categoría</label>
        <select name="categoria_id">
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>">
                    <?= htmlspecialchars($cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Estado</label>
        <select name="estado">
            <option value="borrador">Borrador</option>
            <option value="publicado">Publicado</option>
        </select>

        <label>Contenido</label>
        <div id="editor"></div>
        <input type="hidden" name="contenido" id="contenidoInput">

        <button type="submit">Guardar Post</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        // Blot personalizado para el botón de audio
        const BlockEmbed = Quill.import('blots/block/embed');

        class AudioButtonBlot extends BlockEmbed {
            static create(url) {
                const node = super.create();
                node.setAttribute('contenteditable', 'false');
                node.setAttribute('data-audio-url', url);
                node.innerHTML = '<i class="bi bi-play-circle-fill"></i> Escuchar';
                return node;
            }
            static value(node) {
                return node.getAttribute('data-audio-url');
            }
        }
        AudioButtonBlot.blotName = 'audioButton';
        AudioButtonBlot.tagName = 'button';
        AudioButtonBlot.className = 'shofar-play-btn';
        Quill.register(AudioButtonBlot);

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
                                this.quill.insertEmbed(range.index, 'audioButton', url, Quill.sources.USER);
                                this.quill.setSelection(range.index + 1);
                            }
                        }
                    }
                }
            }
        });

        document.getElementById('postForm').addEventListener('submit', () => {
            document.getElementById('contenidoInput').value = quill.root.innerHTML;
        });
    </script>
</body>

</html>