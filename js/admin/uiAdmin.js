const AdminUI = {
    async cargarCantos() {
        try {
            const cantos = await AdminAPI.obtenerTodos();
            this.mostrarCantos(cantos);
        } catch (error) {
            console.error(error);
            document.getElementById('cantos-list').innerHTML = 
                 '<div class="alert alert-danger">Error cargando cantos</div>';
        }
    },
    
    mostrarCantos(cantos) {
        if (cantos.length === 0) {
            document.getElementById('cantos-list').innerHTML = 
                '<div class="alert alert-info">No hay cantos registrados</div>';
            return;
        }
        
        let html = `
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Interno</th>
                    <th>Categoría</th>
                    <th>Título</th>
                    <th>Estrofas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        `;
        
        cantos.forEach(canto => {
            const estrofasPreview = (canto.letra || '').substring(0, 50) + '...';

            
            html += `
            <tr>
                <td>${canto.id}</td>
                <td>${canto.numero}</td>
                <td>${canto.categoria_id}</td>
                <td>${canto.titulo}</td>
                <td>${estrofasPreview}</td>
                <td>
                    <a href="edit.php?id=${canto.id}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button onclick="AdminUI.eliminarCanto(${canto.id})" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
            `;
        });
        
        html += '</tbody></table>';
        document.getElementById('cantos-list').innerHTML = html;
    },
    
    async eliminarCanto(id) {
        if (confirm('¿Estás seguro de eliminar este canto?')) {
            try {
                await AdminAPI.eliminarCanto(id);
                alert('Canto eliminado correctamente');
                this.cargarCantos();
            } catch (error) {
                alert('Error al eliminar: ' + error);
            }
        }
    }
};