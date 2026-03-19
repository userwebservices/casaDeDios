// menuClicks.js - VERSIÓN 100% FUNCIONAL
import { getLyrics } from '../api/apiLyrics.js';
import { renderizarCancion } from './displayLyrics.js';

function ocultarMenuDesplegado() {
    console.log('🔍 Buscando menús desplegados...');
    
    // Opción A: Convertir a array
    const menus = document.querySelectorAll('.dropdown-menu');
    const menusArray = Array.from(menus);
    
    // Opción B: Usar for...of (más seguro)
    for (const menu of menus) {
        console.log(`📌 Ocultando: ${menu.id || 'sin id'}`);
        menu.style.display = 'none';
        
        // Si Bootstrap necesita resetear clases
        menu.classList.remove('show'); 
    } 
    
    console.log(`✅ ${menusArray.length} menús ocultados`);
}

export function inicializarClicksEnMenu() {
    document.addEventListener('click', async (event) => {
        const item = event.target.closest('.menu-item');
        
        if (item) {
            console.log('🎯 Click en ítem detectado:', item.dataset.id);
            
            // 1. PRIMERO ocultar menús
            ocultarMenuDesplegado();
            
            // 2. Luego procesar el click
            const idCancion = item.dataset.id;
            
            if (!idCancion) {
                console.error('❌ No hay data-id en el ítem');
                return;
            }
            
            try {
                const cancion = await getLyrics(idCancion);
                renderizarCancion(cancion);
            } catch (error) {
                console.error('❌ Error:', error);
            }
        }
    });
}