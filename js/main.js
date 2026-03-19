import { MenuLoader } from './menuLoader.js';
import { HeroLoader } from './heroLoader.js';  // ← LÍNEA NUEVA 1

import { __AppState__ } from './modules/stateManager.js';
import { __SongHandlers__ } from './modules/songHandlers.js';
import { __MenuHandlers__ } from './modules/menuHandlers.js';
 
document.addEventListener('DOMContentLoaded', async () => {
    // Elementos del DOM
    const displaySection = document.querySelector('.display');
    const contentContainer = document.getElementById('contentContainer');
    const titleAimCover = document.getElementById('titleAimCover');
    
    // Configurar vista de bienvenida
    displaySection.classList.add('welcome-view');
    displaySection.style.backgroundImage = 'url("assets/bg/cover/YedidBg.webp")';
    
    // Crear instancias
    const appState = new __AppState__();
    const songHandlers = new __SongHandlers__(displaySection, contentContainer, titleAimCover);
    const menuHandlers = new __MenuHandlers__(appState, songHandlers);
    const menuLoader = new MenuLoader();
        const heroLoader = new HeroLoader();  // ← LÍNEA NUEVA 2

        // Cargar hero dinámicamente
    await heroLoader.cargarHeroActivo();  // ← LÍNEA NUEVA 3


    
    // Cargar menú dinámicamente
    await menuLoader.cargarMenu();
    
    // Configurar comportamiento del menú
    menuHandlers.setupMenuHover();
    menuHandlers.setupMenuClicks();
    menuHandlers.setupTouchSupport();
    menuHandlers.setupMobileMenu();  // ← AGREGAR ESTA LÍNEA

    

    
    /* Mensaje de bienvenida
    if (titleAimCover) {
        titleAimCover.innerHTML = '<h1>¡Todo lo que respira alabe al Señor!</h1>';
    }
        */
}); 