<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje Hero</title>
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
        <h2 class="mb-4">Nuevo Mensaje de Bienvenida</h2>

        <form id="form-hero" class="row g-3">
            <div class="col-12">
                <label class="form-label">Nombre descriptivo</label>
                <input type="text" name="nombre" class="form-control" placeholder="ej: Mensaje Omer 2025" required>
            </div>

            <div class="col-12">
                <label class="form-label">Título (H1)</label>
                <textarea name="titulo" class="form-control" rows="2" required></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Subtítulo H2 (opcional)</label>
                <textarea name="subtitulo_h2" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Subtítulo H4 (opcional)</label>
                <textarea name="subtitulo_h4" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label">Referencia H5 (opcional)</label>
                <input type="text" name="referencia_h5" class="form-control" placeholder="ej: Gal.5:22">
            </div>

            <div class="col-md-4">
                <label class="form-label">Ícono título (opcional)</label>
                <input type="text" name="icono_titulo" class="form-control" placeholder="ej: fas fa-heart">
            </div>

            <div class="col-md-4">
                <label class="form-label">Ícono referencia (opcional)</label>
                <input type="text" name="icono_referencia" class="form-control" placeholder="ej: fas fa-book">
            </div>

            <div class="col-12">
                <label class="form-label">Imagen de fondo</label>
                <input type="text" name="imagen_bg" class="form-control" placeholder="ej: YedidBg.webp" required>
                <small class="text-muted">Archivo en assets/bg/cover/</small>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" name="activa" class="form-check-input" id="activa" value="1">
                    <label class="form-check-label" for="activa">
                        Activar este mensaje (se desactivarán los demás)
                    </label>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="hero-config.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="../../js/admin/apiAdmin.js"></script>
    <script>
        document.getElementById('form-hero').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            data.activa = data.activa ? 1 : 0;

            AdminAPI.crearHeroConfig(data)
                .then(response => {
                    alert('Mensaje creado correctamente');
                    window.location.href = 'hero-config.php';
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
        });
    </script>
</body>

</html>