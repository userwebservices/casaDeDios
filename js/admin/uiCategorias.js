const UICategoria = {
    async cargarCategorias() {
        try {
            const categorias = await AdminAPI.obtenerTodasCategorias();
            this.mostrarCategorias(categorias);
        } catch (error) {
            console.error(error);
            document.getElementById('categorias-list').innerHTML = 
                '<div class="alert alert-danger">Error cargando categorías</div>';
        }
    },

    mostrarCategorias(categorias) {
        if (categorias.length === 0) {
            document.getElementById('categorias-list').innerHTML = 
                '<div class="alert alert-info">No hay categorías registradas</div>';
            return;
        }

        let html = `
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Orden</th>
                    <th>Activa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        `;

        categorias.forEach(cat => {
            html += `
            <tr>
                <td>${cat.id}</td>
                <td>${cat.nombre}</td>
                <td>${cat.slug}</td>
                <td>${cat.orden}</td>
                <td>${cat.activa ? 'Sí' : 'No'}</td>
                <td>
                    <a href="categoria-edit.php?id=${cat.id}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button onclick="UICategoria.eliminarCategoria(${cat.id})" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
            `;
        });

        html += '</tbody></table>';
        document.getElementById('categorias-list').innerHTML = html;
    },

    async eliminarCategoria(id) {
        if (confirm('¿Estás seguro de eliminar esta categoría?')) {
            try {
                await AdminAPI.eliminarCategoria(id);
                alert('Categoría eliminada correctamente');
                this.cargarCategorias();
            } catch (error) {
                alert('Error al eliminar: ' + error);
            }
        }
    }
};