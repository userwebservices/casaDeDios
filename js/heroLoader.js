//Manipulo la respuesta del server, y presnta los datos en casadedios.mx/cantos.html
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

            // Bloques libres (h1..h6 en el orden que se escriban) o estructura clásica
            

            if (hero.bloques && hero.bloques.trim()) {
                hero.bloques.split(/\r?\n/).forEach(linea => {
                    const m = linea.match(/^\s*(h[1-6])(?:\.([\w-]+))?\s*:\s*(.+)$/i);
                    if (m) {
                        const tag = m[1].toLowerCase();
                        const cls = m[2] ? ` class="${m[2]}"` : '';
                        html += `<${tag}${cls}>${m[3].trim()}</${tag}>`;
                    }
                });
            }
            
            
            else {
                // Orden clásico: h1 arriba -> h2 -> h4
                html += `<h1>${hero.titulo}</h1>`;
                if (hero.subtitulo_h2) html += `<h2>${hero.subtitulo_h2}</h2>`;
                if (hero.subtitulo_h4) html += `<h4>${hero.subtitulo_h4}</h4>`;
            }

            // Referencia con ícono
            if (hero.referencia_h5) {
                html += '<div class="overlaidInsideVerse">';
                html += '<button class="verseBadge" type="button">';
                if (hero.icono_referencia) {
                    html += `<i class="${hero.icono_referencia}"></i> `;
                }
                html += `<h5>${hero.referencia_h5}</h5>`;
                html += '</button>';
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