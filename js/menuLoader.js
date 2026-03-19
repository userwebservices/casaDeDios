export class MenuLoader {
    constructor() {
        this.API_BASE = 'php/apiAdmin.php';
    }

    async cargarMenu() {
        try {
            // Obtener categorías
            const response = await fetch(`${this.API_BASE}?action=getCategorias`);
            const categorias = await response.json();
            
            const menuContainer = document.getElementById('menuContainer');
            
            for (const cat of categorias) {
                // Obtener cantos de esta categoría
                const cantosRes = await fetch(`${this.API_BASE}?action=getCantosPorCategoria&slug=${cat.slug}`);
                const cantos = await cantosRes.json();
                
                // Crear el item del menú
                const menuItem = this.crearMenuItem(cat, cantos);
                menuContainer.appendChild(menuItem);
            }
        } catch (error) {
            console.error('Error cargando menú:', error);
        }
    }

    crearMenuItem(categoria, cantos) {
        const li = document.createElement('li');
        li.className = 'nav-item dropdown';
        
        li.innerHTML = `
            <a class="nav-link dropdown-toggle" href="#" role="button"
               data-toggle="dropdown" data-category="${categoria.slug}">
                ${categoria.nombre}
            </a>
            <div class="dropdown-menu mega-dropdown">
                <div class="dropdown-grid-container ps-2">
                    ${this.crearColumnas(cantos, categoria.slug)}
                </div>
            </div>
        `;
        
        return li;
    }

    crearColumnas(cantos, categoria) {
        const cantosPorColumna = 20;
        let html = '';
        
        for (let i = 0; i < cantos.length; i += cantosPorColumna) {
            const grupo = cantos.slice(i, i + cantosPorColumna);
            html += '<div class="dropdown-links" style="--custom-width: 200px">';
            
            grupo.forEach(canto => {
                html += `
                    <a class="dropdown-item custom-font-item" href="#" 
                       data-song-id="${canto.numero}" data-category="${categoria}">
                        ${canto.numero}. ${canto.titulo}
                    </a>
                `;
            });
            
            html += '</div>';
        }
        
        return html;
    }
}