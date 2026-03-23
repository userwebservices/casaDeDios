export class BuscadorCantos {
    constructor(menuHandlers) {
        this.API_BASE = 'php/apiAdmin.php';
        this.menuHandlers = menuHandlers; // 👈 DEBE asignarse
        this.input = document.getElementById('searchInput');
        this.results = document.getElementById('searchResults');
        this.timeout = null;

        this.init();
    }

    init() {
        this.input.addEventListener('keyup', (e) => {
            const value = e.target.value.trim();

            clearTimeout(this.timeout);

            if (value.length < 3) {
                this.results.style.display = 'none';
                return;
            }

            // debounce (espera 300ms)
            this.timeout = setTimeout(() => {
                this.buscar(value);
            }, 300);
        });

        // ocultar si haces click fuera
        document.addEventListener('click', (e) => {
            if (!this.input.contains(e.target)) {
                this.results.style.display = 'none';
            }
        });
    }

    async buscar(query) {
    try {        
        const res = await fetch(`${this.API_BASE}?action=buscarCantos&q=${encodeURIComponent(query)}`);        
        const resJOSN = await res.json();

        //console.log('RAW RESPONSE:', text);
        this.renderResultados(resJOSN);

    } catch (error) {
        console.error('Error en búsqueda:', error);
    }
}

    renderResultados(items) {
        this.results.innerHTML = '';

        if (!items.length) {
            this.results.style.display = 'none';
            return;
        }

        items.forEach(item => {
            const a = document.createElement('a');
            a.className = 'list-group-item list-group-item-action';
            a.textContent = `${item.numero || ''} - ${item.titulo}`;

            a.addEventListener('click', () => {
                this.seleccionar(item);
            });

            this.results.appendChild(a);
        });

        this.results.style.display = 'block';
    }

    async seleccionar(item) {

        if (!this.menuHandlers) {
            console.error('menuHandlers no definido');
            return;
        }

        this.input.value = item.titulo;
        this.results.style.display = 'none';

        try {
            await this.menuHandlers.handleSongSelection(
                item.categoria, // slug de categoría
                item.numero     // songId
            );

            // cerrar dropdowns (igual que menú)
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });

        } catch (error) {
            console.error('Error seleccionando canto:', error);
        }
    }
}