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
    <title>Admin - Hero Config</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Hero Config</a>
            <div>
                <a href="index.php" class="btn btn-outline-light me-2">Cantos</a>
                <a href="categorias.php" class="btn btn-outline-light me-2">Categorías</a>
                <a href="logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Mensajes de Bienvenida</h2>
            <a href="hero-create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Mensaje
            </a>
        </div>

        <div id="hero-list" class="table-responsive">
            <!-- Se cargará dinámicamente -->
        </div>
    </div>

    <script src="../../js/admin/apiAdmin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const configs = await AdminAPI.obtenerTodosHeroConfig();
                mostrarConfigs(configs);
            } catch (error) {
                document.getElementById('hero-list').innerHTML =
                    '<div class="alert alert-danger">Error cargando configuraciones</div>';
            }
        });

        function mostrarConfigs(configs) {
            if (configs.length === 0) {
                document.getElementById('hero-list').innerHTML =
                    '<div class="alert alert-info">No hay mensajes registrados</div>';
                return;
            }

            let html = `
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Título</th>
                            <th>Imagen</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            configs.forEach(config => {
                const activa = config.activa ?
                    '<span class="badge bg-success">Activa</span>' :
                    '<span class="badge bg-secondary">Inactiva</span>';

                html += `
                    <tr>
                        <td>${config.nombre}</td>
                        <td>${config.titulo.substring(0, 50)}...</td>
                        <td>${config.imagen_bg}</td>
                        <td>${activa}</td>
                        <td>
                            ${!config.activa ?
                        `<button onclick="activar(${config.id})" class="btn btn-sm btn-success">Activar</button>` :
                        ''}
                            <a href="hero-edit.php?id=${config.id}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button onclick="eliminar(${config.id})" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            document.getElementById('hero-list').innerHTML = html;
        }

        async function activar(id) {
            if (confirm('¿Activar este mensaje? (Se desactivarán los demás)')) {
                try {
                    await AdminAPI.activarHeroConfig(id);
                    location.reload();
                } catch (error) {
                    alert('Error: ' + error);
                }
            }
        }

        async function eliminar(id) {
            if (confirm('¿Eliminar este mensaje?')) {
                try {
                    await AdminAPI.eliminarHeroConfig(id);
                    location.reload();
                } catch (error) {
                    alert('Error: ' + error);
                }
            }
        }
    </script>
</body>

</html>
```

Guarda y abre:
```
http://localhost:8888/menu2026/php/admin/hero-config.php