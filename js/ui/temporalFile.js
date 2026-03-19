import { fetchTitles } from '../api/apiTitles.js';
import { displayTitles } from './displayMenu.js';

export function hoverMenu() {

    const botones = document.querySelectorAll('.categoria-btn');

    botones.forEach(btn => {
    
        const categoria = btn.dataset.categoria; 
        const contenedor = document.querySelector(btn.dataset.target); 
        btn.addEventListener('mouseenter', async () => {
            contenedor.style.display = 'block'; 
            
            try {
                const items = await fetchTitles(categoria); 
                
                displayTitles(contenedor, items);
            } catch (error) {
                console.error('Error al cargar los cantos:', error);
                const errorItems = [{ title: 'Error al cargar', id: 0 }];
                displayTitles(contenedor, errorItems);
            }
        });

        btn.addEventListener('mouseleave', () => {
            ocultarConDelay(btn, contenedor);
        });

        contenedor.addEventListener('mouseleave', () => {
            ocultarConDelay(btn, contenedor);
        });
    });
}

function ocultarConDelay(btn, contenedor) {
    
    setTimeout(() => {
        if (!btn.matches(':hover') && !contenedor.matches(':hover')) {
            contenedor.style.display = 'none';
        }
    }, 150);
}