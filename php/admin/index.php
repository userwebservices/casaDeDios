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
    <title>Admin - Cantos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Cantos</a>
            <a href="logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestión de Cantos</h2>
            <a href="create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Canto
            </a>
        </div>

        <div id="cantos-list" class="table-responsive">
            <!-- Los cantos se cargarán aquí con JavaScript -->
        </div>
    </div>

    <script src="../../js/admin/apiAdmin.js"></script>
    <script src="../../js/admin/uiAdmin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AdminUI.cargarCantos();
        });
    </script>
</body>

</html>