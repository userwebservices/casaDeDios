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
    <title>Admin - Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Categorías</a>
            <div>
                <a href="index.php" class="btn btn-outline-light me-2">Cantos</a>
                <a href="logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestión de Categorías</h2>
            <a href="categoria-create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Categoría
            </a>
        </div>

        <div id="categorias-list" class="table-responsive">
            <!-- Las categorías se cargarán aquí -->
        </div>
    </div>

    <script src="../../js/admin/apiAdmin.js"></script>
    <script src="../../js/admin/uiCategorias.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            UICategoria.cargarCategorias();