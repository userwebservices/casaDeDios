export class HeroLoader {
    constructor() {
        this.API_BASE = 'php/apiAdmin.php';
    }

    async cargarHeroActivo() {
        try {
            const response = await fetch(`${this.API_BASE}?action=getActiveHeroConfig`);
            const hero = await response.json();
            
            if (hero) {
                this.renderHero(hero);
            } else {
                // Mensaje por defecto si no hay ninguno activo
                this.renderDefault();
            }
        } catch (error) {
            console.error('Error cargando hero:', error);
            this.renderDefault();
        }
    }

    renderHero(hero) {
        const titleAimCover = document.getElementById('titleAimCover');
        const displaySection = document.querySelector('.display');
        
        // Actualizar imagen de fondo
        if (hero.imagen_bg) {
            displaySection.style.backgroundImage = `url("assets/bg/cover/${hero.imagen_bg}")`;
        }
        
        // Construir HTML
        let html = '<div class="overlaidTitle">';
        
        // Ícono + Título
        if (hero.icono_titulo) {
            html += `<i class="${hero.icono_titulo}"></i> `;
        }
        html += `<h1>${hero.titulo}</h1>`;
        
        // Subtítulos opcionales
        if (hero.subtitulo_h2) {
            html += `<h2>${hero.subtitulo_h2}</h2>`;
        }
        if (hero.subtitulo_h4) {
            html += `<h4>${hero.subtitulo_h4}</h4>`;
        }
        
        // Referencia con ícono
        if (hero.referencia_h5) {
            html += '<div class="overlaidInsideVerse">';
            if (hero.icono_referencia) {
                html += `<i class="${hero.icono_referencia}"></i> `;
            }
            html += `<h5>${hero.referencia_h5}</h5>`;
            html += '</div>';
        }
        
        html += '</div>';
        titleAimCover.innerHTML = html;
    }

    renderDefault() {
        const titleAimCover = document.getElementById('titleAimCover');
        titleAimCover.innerHTML = '<h1>¡Todo lo que respira alabe al Señor!</h1>';
    }
}