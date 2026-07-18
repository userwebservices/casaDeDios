export class MenuLoader {
    constructor() {
        this.API_BASE = 'php/apiAdmin.php';
    }

    async cargarMenu() {
    const cacheKey = 'himnario_menu';
    const cached = sessionStorage.getItem(cacheKey);
    if (cached) {
        this.pintarMenu(JSON.parse(cached));
        return;
    }

    try {
        const menuContainer = document.getElementById('menuContainer');
        menuContainer.innerHTML = `...placeholders...`;

        const response = await fetch(`${this.API_BASE}?action=getCategorias`);
        const categorias = await response.json();

        const data = [];
        for (const cat of categorias) {
            const cantosRes = await fetch(`${this.API_BASE}?action=getCantosPorCategoria&slug=${cat.slug}`);
            const cantos = await cantosRes.json();
            if (!cantos || cantos.length === 0) continue;
            data.push({ cat, cantos });
        }

        sessionStorage.setItem(cacheKey, JSON.stringify(data));
        this.pintarMenu(data);
    } catch (error) {
        console.error('Error cargando menú:', error);
    }
}

pintarMenu(data) {
    const menuContainer = document.getElementById('menuContainer');
    menuContainer.innerHTML = '';
    data.forEach(({ cat, cantos }) => {
        const menuItem = this.crearCategoriaButton(cat, cantos);
        menuContainer.appendChild(menuItem);
    });
}

    crearCategoriaButton(categoria, cantos) {
        const button = document.createElement('button');

        button.className =
            'btn btn-outline-info rounded-pill';

        button.textContent =
            categoria.nombre;

        button.addEventListener('click', () => {
            this.abrirModalCategoria(
                categoria,
                cantos
            );
        });

        return button;
    }

    abrirModalCategoria(categoria, cantos) {

        document.getElementById(
            'songsModalTitle'
        ).textContent =
            categoria.nombre;

        document.getElementById('songsModalBody').innerHTML =
        cantos.map(c => `
            <button
                class="song-card"
                data-song-id="${c.numero}"
                data-category="${categoria.slug}">

                <span class="song-number">
                    ${c.numero}
                </span>

                <span class="song-title">
                    ${c.titulo}
                </span>

            </button>
        `).join('');
            

        const modal =
            new bootstrap.Modal(
                document.getElementById(
                    'songsModal'
                )
            );

        modal.show();
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