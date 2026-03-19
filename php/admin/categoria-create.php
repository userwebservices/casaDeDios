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
    <title>Nueva Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Categorías</a>
            <div>
                <a href="categorias.php" class="btn btn-outline-light">Volver</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Nueva Categoría</h2>

        <form id="form-categoria" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" required>
                <small class="text-muted">Ejemplo: himnos, adoracion, textos</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Orden</label>
                <input type="number" name="orden" class="form-control" value="0" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Activa</label>
                <select name="activa" class="form-control" required>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="categorias.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="../../js/admin/apiAdmin.js"></script>
    <script>
        document.getElementById('form-categoria').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            AdminAPI.crearCategoria(data)
                .then(response => {
                    alert('Categoría creada correctamente');
                    window.location.href = 'categorias.php';
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
        });
    </script>
</body>

</html>