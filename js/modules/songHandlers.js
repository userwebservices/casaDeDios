// js/modules/songHandlers.js
export class __SongHandlers__ {
    constructor(displaySection, contentContainer, titleAimCover) {
        this.displaySection = displaySection;
        this.contentContainer = contentContainer;
        this.titleAimCover = titleAimCover;
    }
    
    renderSong(song) {
        this.contentContainer.innerHTML = '';
        
        // Ocultar overlay estático y mostrar dinámico
        document.querySelector('.display-overlay-static').classList.add('hidden');
        
        this.updateBackground(song);
        this.createDynamicOverlay();
        
        const fragment = document.createDocumentFragment();
        const container = document.createElement('div');
        container.innerHTML = `${song.title || ''}${song.estrofas || ''}`;
        fragment.appendChild(container);
        this.contentContainer.appendChild(fragment);
    }
    
    updateBackground(song) {
        const DEFAULT_BG_IMAGE = 'assets/bg/default/bg-default-01.webp';
        const hasCustomBg = song['bg-img'] && song['bg-img'].trim() !== '';
        
        document.querySelector('.display').style.backgroundImage = hasCustomBg
            ? `url('${song['bg-img']}')`
            : `url('${DEFAULT_BG_IMAGE}')`;
        
            document.querySelector('.display')
            .style.backgroundSize = 'cover';
            document.querySelector('.display')
            .style.backgroundPosition = 'center';
            document.querySelector('.display')
            .style.backgroundAttachment = 'fixed';
            document.querySelector('.display')
            .style.backgroundRepeat = 'no-repeat';
    }
    
    createDynamicOverlay() {
        document.querySelectorAll('.display-overlay-dynamic').forEach(overlay => {
            overlay.remove();
        });
        
        const overlay = document.createElement('div');
        overlay.className = 'display-overlay-dynamic';
        document.querySelector('.display')
.appendChild(overlay);
    }
    




    resetToWelcomeScreen() {
    const displaySection = document.querySelector('.display');
    displaySection.classList.add('welcome-view');
    displaySection.style.backgroundImage = 'url("assets/bg/cover/YedidBg.webp")';
    
    const titleAimCover = document.getElementById('titleAimCover');
    if (titleAimCover) {
        titleAimCover.style.display = 'flex';
        titleAimCover.style.visibility = 'visible';  // ← AGREGAR
        titleAimCover.style.opacity = '1';           // ← AGREGAR
        titleAimCover.innerHTML = '<h1>¡Todo lo que respira alabe al Señor!</h1>';
    }
}
    
    


    hideWelcomeMessage() {
        const displaySection = document.querySelector('.display');
        displaySection.classList.remove('welcome-view');
        
        const titleAimCover = document.getElementById('titleAimCover');
        if (titleAimCover) {
            titleAimCover.style.display = 'none';
            titleAimCover.style.visibility = 'hidden';  // ← AGREGAR
            titleAimCover.style.opacity = '0';          // ← AGREGAR
        }
    }

    










}