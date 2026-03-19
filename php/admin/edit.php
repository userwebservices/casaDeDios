<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Canto</title>
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
        <h2 class="mb-4">Editar Canto</h2>

        <div id="loading" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>

        <form id="form-canto" class="row g-3" style="display: none;">
            <input type="hidden" name="id" id="canto-id">
            <div class="col-md-6">
                <label class="form-label">Numero</label>
                <input type="number" name="numero" id="numero" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Categoría</label>
                <select name="categoria_id" id="categoria_id" class="form-control" required>
                    <option value="">Selecciona una categoría</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Estrofas (una por línea, separa con |)</label>
                <textarea name="letra" id="letra" class="form-control" rows="10" required></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Imagen de fondo (opcional)</label>
                <input type="text" name="bg_img" id="bg_img" class="form-control">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="../../js/admin/apiAdmin.js"></script>
    <script>
        const cantoId = <?php echo $id; ?>;

        document.addEventListener('DOMContentLoaded', function () {
            // Primero cargar categorías
            AdminAPI.obtenerCategorias()
                .then(categorias => {
                    const select = document.getElementById('categoria_id');
                    categorias.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.id;
                        option.textContent = cat.nombre;
                        select.appendChild(option);
                    });

                    // DESPUÉS cargar el canto
                    return AdminAPI.obtenerCanto(cantoId);
                })
                .then(canto => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('form-canto').style.display = 'block';

                    document.getElementById('canto-id').value = canto.id;
                    document.getElementById('numero').value = canto.numero;
                    document.getElementById('categoria_id').value = canto.categoria_id;
                    document.getElementById('titulo').value = canto.titulo;
                    document.getElementById('letra').value = canto.letra;
                    document.getElementById('bg_img').value = canto.bg_img || '';
                })
                .catch(error => {
                    alert('Error cargando canto: ' + error);
                    window.location.href = 'index.php';
                });
        });


        // AGREGAR ESTE BLOQUE:
        document.getElementById('form-canto').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            AdminAPI.actualizarCanto(data)
                .then(response => {
                    alert('Canto actualizado correctamente');
                    window.location.href = 'index.php';
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
        });


    </script>
</body>

</html>