export function renderizarCancion(cancion) {
    // 🔍 DEBUG - Quita después
    console.log('🎵 Datos completos:', cancion);
    console.log('📷 imagen_path:', cancion.imagen_path);
    console.log('🏷️ categoria_slug:', cancion.categoria_slug);
    console.log('✅ Evaluación:', !!cancion.imagen_path);
    const contenedor = document.getElementById('contenido-cancion');
    
    contenedor.innerHTML = ` 
        <article class="cancion-completa">
            <header class="cancion-header">
                <h1 class="cancion-titulo">${cancion.titulo}</h1>
            </header>
                <div class="cancion-cuerpo">
                    ${cancion.imagen_path ? 
                      `<img src="assets/bg/${cancion.categoria_slug}/${cancion.imagen_path}" 
                            alt="${cancion.titulo}" 
                            class="cancion-imagen">`
                    : ''}
                <div class="cancion-letra">
                    ${formatearLetra(cancion.letra)} 
                </div>
            </div>
        </article>
    `;
} 
 
function formatearLetra(letra) {
    // Agrega validación por si acaso
    if (!letra) return '';
    return letra.split('\n').map(parrafo => 
        `<p class="estrofa">${parrafo}</p>`
    ).join('');
}