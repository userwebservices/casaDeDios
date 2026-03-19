/* 🧠 Observa algo importante 
Este archivo NO sabe de:
🟢 Evento del hover
🟢 No sabe de la llamada a la API
✅ PERO PINTA LOS DATOS QUE VIENE DE LA LLAMADA DE LA API! 

<!-- Puedo mandar a llamar la demás información del php ${item.estrofas}-->

 
Solo recibe: (contenedor, datos) 
📌 Esto lo hace reutilizable. 
 
NO SE EJECUTA AQUÍ! Sólo es la función, tiene parametros, pero aquí sus parametros sólo existen en forma de molde
Donde se ejecute la fución, ahí DEBE DE HABER PARAMETROS!!

*/

export function displayTitles(contenedor, items) {
    contenedor.innerHTML = `
        <div class="menu-grid">
            ${items.map(item => `
                <div class="menu-item" data-id="${item.id}">
                    ${item.title} 
                    <!-- Puedo mandar a llamar la demás información del php ${item.estrofas}-->
                </div>
            `).join('')}
        </div>
    `;
}
