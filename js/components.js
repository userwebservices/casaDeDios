//Botón back to top 
document.addEventListener('DOMContentLoaded', () => {
    //console.log('DOMContentLoaded ejecutado');
    const homeButton = document.getElementById('homeButton');
    const scrollThreshold = 200;
      //  console.log('homeButton encontrado:', homeButton);

    
    if (!homeButton) {
        console.warn('homeButton no encontrado');
        return;
    }
    
    // Escuchar scroll en WINDOW
    window.addEventListener('scroll', () => {
        const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollPosition > scrollThreshold) {
            homeButton.classList.add('visible');
        } else {
            homeButton.classList.remove('visible');
        }
    });
    
            // Click - volver arriba
            homeButton.addEventListener('click', (e) => {
            e.preventDefault();
            homeButton.style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                homeButton.style.transform = 'scale(1)';
                
                // Ir al home (recargar página)
                window.location.href = window.location.origin + window.location.pathname;
                
                // O si prefieres solo recargar:
                // location.reload();
            }, 100);
        });
});
//  E N D  Botón back to top