// js/modules/stateManager.js
export class __AppState__ {
    constructor() {
        this.currentSong = null;
        this.songsCache = new Map();
        this.jsonData = null;
        this.API_BASE = 'php/api.php';
    }

    async loadJsonData() {
    const cacheKey = 'himnario_jsonData';
    const cached = sessionStorage.getItem(cacheKey);
    if (cached) {
        this.jsonData = JSON.parse(cached);
        return;
    }

    try {
        const categoriasRes = await fetch(`${this.API_BASE}?action=getCategorias`);
        const categorias = await categoriasRes.json();

        this.jsonData = {};

        for (const cat of categorias) {
            const cantosRes = await fetch(`${this.API_BASE}?action=getCantosPorCategoria&slug=${cat.slug}`);
            const cantos = await cantosRes.json();

            this.jsonData[cat.slug] = cantos.map(canto => ({
                id: canto.numero,
                title: `<h1 class='titleDark'>${canto.numero} ${canto.titulo}</h1>`,
                estrofas: `<article class='contentDark'>${formatearLetra(canto.letra)}</article>`,
                'bg-img': canto.bg_img ? `assets/bg/${cat.slug}/${canto.bg_img}` : ''
            }));
        }

        sessionStorage.setItem(cacheKey, JSON.stringify(this.jsonData));
    } catch (error) {
        console.error('Error cargando datos:', error);
        throw error;
    }
}
 
    findSong(category, songId) {
        if (!this.jsonData[category]) {
            throw new Error(`Categoría ${category} no encontrada`);
        }
        
        const song = this.jsonData[category].find(item => item.id == songId);
        
        if (!song) {
            throw new Error(`Canción ID ${songId} no encontrada en ${category}`);
        }
        
        return song;
    }
}




function formatearLetra(letra) {
    if (!letra) return '';
    return letra.split(/\r?\n/).map(linea => {
        if (linea.startsWith('#')) {
            const texto = linea.slice(1).trim();
            return `<span class="estrofa-hebreo">${texto}</span><br>`;
        }
        return `<span class="estrofa-traduccion">${linea}</span><br>`;
    }).join('');
}