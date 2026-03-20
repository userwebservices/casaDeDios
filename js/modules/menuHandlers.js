// js/modules/menuHandlers.js
export class __MenuHandlers__ {
    constructor(appState, songHandlers) {
        this.appState = appState;
        this.songHandlers = songHandlers;
    }
    
    setupMenuHover() {
        const dropdowns = document.querySelectorAll('.nav-item.dropdown');
        
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('mouseenter', () => {
                dropdown.querySelector('.dropdown-menu').classList.add('show');
            });
            
            dropdown.addEventListener('mouseleave', () => {
                dropdown.querySelector('.dropdown-menu').classList.remove('show');
            });
        });
    }
    
            setupMenuClicks() {
                document.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('click', async (e) => {
                        e.preventDefault();
                        
                        this.resetScrollPosition();
                        
                        const songId = item.dataset.songId;
                        const category = item.closest('.dropdown-menu')
                                        .previousElementSibling.dataset.category;
                        
                        await new Promise(resolve => setTimeout(resolve, 50));
                        await this.handleSongSelection(category, songId);
                        
                        // Cerrar todos los dropdowns
                        document.querySelectorAll('.dropdown-menu').forEach(menu => {
                            menu.classList.remove('show');
                        });

                        // Cerrar menú hamburguesa en mobile (Bootstrap 5)
                        const navbarCollapse = document.querySelector('#navbarSupportedContent');
                        if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse) || new bootstrap.Collapse(navbarCollapse, { toggle: false });
                            bsCollapse.hide();
                        }
                    });
                });
            }
    
    setupTouchSupport() {
    if ('ontouchstart' in window) {
        // Click en categoría para abrir/cerrar
        document.querySelectorAll('.nav-item.dropdown > a').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const menu = trigger.nextElementSibling;
                const wasOpen = menu.classList.contains('show');
                
                // Cerrar TODOS los menús primero
                document.querySelectorAll('.dropdown-menu').forEach(m => {
                    m.classList.remove('show');
                });
                
                // Si estaba cerrado, abrirlo
                if (!wasOpen) {
                    menu.classList.add('show');
                }
            });
        });
        

        


        // Click fuera cierra todos
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.nav-item.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    }
}
    
    
    /*
    resetScrollPosition() {
        const displayElement = document.querySelector('.display');
        if (displayElement) {
            displayElement.scrollIntoView({ behavior: 'smooth' });
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        setTimeout(() => {
            window.scrollTo(0, 0);
        }, 500);
    }
        */


        resetScrollPosition() {
        // Scroll suave al inicio
        window.scrollTo({ 
            top: 0, 
            behavior: 'instant' // ← Cambiar de 'smooth' a 'instant' para evitar el "saltito" visual
        });
        
        const displayElement = document.querySelector('.display');
        if (displayElement) {
            displayElement.scrollTop = 0; // ← Asegurar que .display también esté arriba
        }
    }
    
    async handleSongSelection(category, songId) {
        try {
            this.songHandlers.hideWelcomeMessage();
            
            if (!this.appState.jsonData) {
                await this.appState.loadJsonData();
            }
            
            const song = this.appState.findSong(category, songId);
            this.songHandlers.renderSong(song);
            
            this.appState.currentSong = { category, id: songId };
            
        } catch (error) {
            console.error('Error:', error);
            this.songHandlers.contentContainer.innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar la canción: ${error.message}
                </div>
            `;
            this.songHandlers.resetToWelcomeScreen();
        }
    }




                setupMobileMenu() {
            // Cerrar todos los dropdowns al cargar si es móvil
            if (window.innerWidth <= 768) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
            
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('#navbarSupportedContent');
            
            if (navbarToggler && navbarCollapse) {
                // Cerrar dropdowns cuando se abre/cierra el menú hamburguesa
                navbarToggler.addEventListener('click', () => {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                });
                
                // ✨ NUEVO: Cerrar menú al hacer click/touch fuera de él
                document.addEventListener('click', (e) => {
                    const isMenuOpen = navbarCollapse.classList.contains('show');
                    const clickedInsideMenu = navbarCollapse.contains(e.target);
                    const clickedToggler = navbarToggler.contains(e.target);
                    
                    // Si el menú está abierto Y el click fue fuera del menú Y no fue en el toggler
                    if (isMenuOpen && !clickedInsideMenu && !clickedToggler) {
                        // Cerrar menú principal
                        navbarCollapse.classList.remove('show');
                        
                        // También cerrar todos los dropdowns abiertos
                        document.querySelectorAll('.dropdown-menu.show').forEach(dropdown => {
                            dropdown.classList.remove('show');
                        });
                    }
                });
            }
        }








}


