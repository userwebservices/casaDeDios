ALTER TABLE hero_config ADD COLUMN bloques TEXT NULL;
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
$id = (int) ($_GET['id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mensaje Hero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Hero Config</a>
            <div>
                <a href="hero-config.php" class="btn btn-outline-light">Volver</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <h2 class="mb-4">Editar Mensaje de Bienvenida</h2>

        <form id="form-hero" class="row g-3">
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

            <div class="col-12">
                <label class="form-label">Nombre descriptivo</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label">Título (H1)</label>
                <textarea name="titulo" id="titulo" class="form-control" rows="2" required></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Bloques (opcional, orden libre)</label>
                <textarea name="bloques" id="bloques" class="form-control" rows="6"
                    placeholder="h2: Bienvenidos&#10;h1: Casa de Dios&#10;h2: Shabat Shalom&#10;h1: Todos a la mesa"></textarea>
                <small class="text-muted">Una línea por bloque, formato <code>h1: texto</code>. Si lo llenas, se ignoran
                    los campos H1/H2/H4.</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Subtítulo H2 (opcional)</label>
                <textarea name="subtitulo_h2" id="subtitulo_h2" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Subtítulo H4 (opcional)</label>
                <textarea name="subtitulo_h4" id="subtitulo_h4" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label">Referencia H5 (opcional)</label>
                <input type="text" name="referencia_h5" id="referencia_h5" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Ícono título (opcional)</label>
                <input type="text" name="icono_titulo" id="icono_titulo" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Ícono referencia (opcional)</label>
                <input type="text" name="icono_referencia" id="icono_referencia" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label">Imagen de fondo</label>
                <input type="text" name="imagen_bg" id="imagen_bg" class="form-control" required>
                <small class="text-muted">Archivo en assets/bg/cover/</small>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" name="activa" class="form-check-input" id="activa" value="1">
                    <label class="form-check-label" for="activa">Activa</label>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="hero-config.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        const heroId = <?php echo $id; ?>;
        const API = '../apiAdmin.php';
        const campos = ['nombre', 'titulo', 'bloques', 'subtitulo_h2', 'subtitulo_h4',
            'referencia_h5', 'icono_titulo', 'icono_referencia', 'imagen_bg'];

        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const res = await fetch(`${API}?action=getOneHeroConfig&id=${heroId}`);
                const hero = await res.json();
                if (hero.error) throw new Error(hero.error);

                campos.forEach(c => {
                    document.getElementById(c).value = hero[c] ?? '';
                });
                document.getElementById('activa').checked = hero.activa == 1;
            } catch (error) {
                alert('Error cargando mensaje: ' + error.message);
                window.location.href = 'hero-config.php';
            }
        });

        document.getElementById('form-hero').addEventListener('submit', async function (e) {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(this));
            data.activa = data.activa ? 1 : 0;
            data.action = 'updateHeroConfig';

            try {
                const res = await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if (result.error) throw new Error(result.error);
                alert('Mensaje actualizado correctamente');
                window.location.href = 'hero-config.php';
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>

</html>