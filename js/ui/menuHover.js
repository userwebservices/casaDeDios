/*
🧠 Explicación conceptual (esto es CLAVE)
🔹 ¿Por qué este archivo existe?
Porque:
Maneja eventos (El detective de eventos) MANEJA EL EVENTO MOUSEENTER
Y ejecuta las funciones que importa: 
    getTitles ()  que es el 👉 llamada a la API 🚀 -> y le manda los datos a mostrar en formato json


Conecta HTML → lógica
No renderiza
No pide datos reales 
📌 Una responsabilidad clara.
*/
import { fetchTitles } from '../api/apiTitles.js';
import { displayTitles } from './displayMenu.js';

// ====||||====|||| ====|||| ====||||  
//               FUNCIÓN: inicializarMenus()
// ====||||====|||| ====|||| ====||||

//export = "Esta función puede ser usada en otros archivos" | Es como decir: "Oye, esta función está disponible para quien la necesite"
export function hoverMenu() {

    //querySelectorAll = "Busca TODOS los elementos con la clase categoria-btn" | Devuelve un array (lista) con los 3 botones: Himnos, Alabanza, Adoración | Es como decir: "Dame todos los botones del menú"
    const botones = document.querySelectorAll('.categoria-btn');

    // forEach = "Por cada botón, haz esto:" | btn = Es una variable temporal que representa cada botón
    botones.forEach(btn => {
    
        const categoria = btn.dataset.categoria; 
        //btn.dataset.categoria lee el atributo data-categoria="himnos" o "alabanza" o "adoracion" del HTML | Si el botón dice data-categoria="himnos", entonces categoria = "himnos"
        
        //Primer parámetro de la función renderizarMenu(contenedor, items)
        const contenedor = document.querySelector(btn.dataset.target); 
        //btn.dataset.target lee data-target="#menu-himnos" o "#menu-alabanza" o "#menu-adoracion" | querySelector('#menu-himnos') = "Busca el <div> con id="menu-himnos"" | contenedor = el <div> donde se mostrarán los resultados

        //EVENTOS: mouseenter | 🐭 Evento: mouseenter (cuando el mouse ENTRA)
        btn.addEventListener('mouseenter', async () => { // 👈 async = "Esta función puede ESPERAR respuestas" (como esperar la pizza)
            contenedor.style.display = 'block'; 
            //Muestra el contenedor (aunque esté vacío)| Es como abrir la caja de pizza antes de que llegue

            //Segundo parámetro de la función renderizarMenu(contenedor, items)
            
            //🔑 Clave de await: | Sin await, el código seguiría ANTES de recibir los datos. Es como intentar comer la pizza antes de que llegue.

            //try = "Intenta hacer esto, y si falla, captura el error" | await = "ESPERA a que obtenerCantos() termine"
            try {
                const items = await fetchTitles(categoria); // obtenerCantos(categoria) = Llama a la API (más abajo lo vemos) | items = Los datos que regresa la API (un array de canciones)
                
                /*
                console.log('Respuesta de obtenerCantos:', items);
                console.log('Tipo de items:', typeof items);
                console.log('Es array?', Array.isArray(items));
                */

                //Llama a la función que "dibuja" el menú en el HTML | Le pasa: dónde (contenedor) y qué mostrar (items)
                displayTitles(contenedor, items);
            } catch (error) {
                console.error('Error al cargar los cantos:', error);
                // Muestra un mensaje de error en el menú
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
// ====||||====|||| ====|||| ====||||  END      FUNCIÓN: inicializarMenus()

// ====||||====|||| ====|||| ====||||  
//               FUNCIÓN: ocultarConDelay()
// ====||||====|||| ====|||| ====||||
function ocultarConDelay(btn, contenedor) {
    
    setTimeout(() => {
        if (!btn.matches(':hover') && !contenedor.matches(':hover')) {
            contenedor.style.display = 'none';
        }
    }, 150);
}
// ====||||====|||| ====|||| ====||||  END      FUNCIÓN: ocultarConDelay()