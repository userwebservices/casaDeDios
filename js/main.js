import { MenuLoader } from './menuLoader.js';
import { BuscadorCantos } from './modules/buscadorHandler.js';
import { HeroLoader } from './heroLoader.js';  // ← LÍNEA NUEVA 1
import { __AppState__ } from './modules/stateManager.js';
import { __SongHandlers__ } from './modules/songHandlers.js';
import { __MenuHandlers__ } from './modules/menuHandlers.js';
import { initBlog } from './modules/blogHandlers.js';
 
document.addEventListener('DOMContentLoaded', async () => {
    // Elementos del DOM
    const displaySection = document.querySelector('.display');
    const contentContainer = document.getElementById('contentContainer');
    const titleAimCover = document.getElementById('titleAimCover');

    if (displaySection && contentContainer && titleAimCover) {
        // Configurar vista de bienvenida
        displaySection.classList.add('welcome-view');
        displaySection.style.backgroundImage = 'url("assets/bg/cover/10.webp")';

        // Crear instancias
        const appState = new __AppState__();
        const songHandlers = new __SongHandlers__(displaySection, contentContainer, titleAimCover);
        const menuHandlers = new __MenuHandlers__(appState, songHandlers);
        const menuLoader = new MenuLoader();
        const heroLoader = new HeroLoader();

        const buscadorCantos = new BuscadorCantos(menuHandlers);

        await heroLoader.cargarHeroActivo();
        await menuLoader.cargarMenu();

        menuHandlers.setupMenuClicks();
    }

    initBlog(); // fuera del if — corre siempre, en cualquier página
});