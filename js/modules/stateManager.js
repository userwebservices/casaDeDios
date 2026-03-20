// js/modules/stateManager.js
export class __AppState__ {
    constructor() {
        this.currentSong = null;
        this.songsCache = new Map();
        this.jsonData = null;
        this.API_BASE = 'php/api.php';
    }

    async loadJsonData() {
        try {
            // Cargar categorías
            const categoriasRes = await fetch(`${this.API_BASE}?action=getCategorias`);
            const categorias = await categoriasRes.json();
            
            // Crear estructura similar al JSON original
            this.jsonData = {};
            
            // Cargar cantos de cada categoría
            for (const cat of categorias) {
                const cantosRes = await fetch(`${this.API_BASE}?action=getCantosPorCategoria&slug=${cat.slug}`);
                const cantos = await cantosRes.json();
                
                // Mapear campos de MySQL a formato JSON original
                this.jsonData[cat.slug] = cantos.map(canto => ({
                    id: canto.numero,
                    title: `<h1 class='titleDark'>${canto.numero} ${canto.titulo}</h1>`,
                    estrofas: `<article class='contentDark'>${canto.letra.replace(/\n/g, '<br>')}</article>`,  // ← CAMBIO AQUÍ
                    'bg-img': canto.bg_img ? `assets/bg/${cat.slug}/${canto.bg_img}` : ''
                }));
            }
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