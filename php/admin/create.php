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
    <title>Nuevo Canto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Cantos</a>
            <div>
                <a href="index.php" class="btn btn-outline-light">Volver</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Nuevo Canto</h2>

        <form id="form-canto" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Numero</label>
                <input type="number" name="numero" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Categoría</label>
                <select name="categoria_id" id="categoria_id" class="form-control" required>
                    <option value="">Selecciona una categoría</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Estrofas (una por línea, separa con |)</label>
                <textarea name="letra" class="form-control" rows="10" required></textarea>
                <small class="text-muted">Ejemplo: Primera estrofa|Segunda estrofa|Tercera estrofa</small>
            </div>
            <div class="col-12">
                <label class="form-label">Imagen de fondo (opcional)</label>
                <input type="text" name="bg_img" class="form-control"
                    placeholder="ej: bg/categoria/bg-categoria-00.webp">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="../../js/admin/apiAdmin.js"></script>

    <script>

        // Cargar categorías al iniciar
        document.addEventListener('DOMContentLoaded', function () {
            AdminAPI.obtenerCategorias()
                .then(categorias => {
                    const select = document.getElementById('categoria_id');
                    categorias.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.id;
                        option.textContent = cat.nombre;
                        select.appendChild(option);
                    });
                });
        });

        document.getElementById('form-canto').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            AdminAPI.crearCanto(data)
                .then(response => {
                    alert('Canto creado correctamente');
                    window.location.href = 'index.php';
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
        });
    </script>
</body>

</html>