/*
23jan26
Función llamada a la API
❌ NO SE EJECUTA AQUÍ! Aquí esta el molde, la función, pero no se ejecuta, por eso se IMPORTA a otro archivo donde es ejecutada y recibe los parametros que necesita.
- El argumento de la función, puede tener cuaqluier nombre!  
- ⚠️ Aquí NO EXISTEN DATOS, es hasta que se ejecuta cuando TRAE LOS DATOS DE LA RESPUESTA DE LA API
 

✅1. Expoprt
✅2. async function
✅3. await - fetch
✅4. await - titles.json()
✅5. fetch (URL? queryString)
*/
import { cache } from './cache.js';

export async function fetchTitles(categoria) {
    /* Construyendo la URL
    
        php/apiTitles.php?categoria=alabanza || adoracion || himnos
        └──┬────────────┘└─┬──────────────-------------------------┘
        │                  │
        Ruta del script    Parámetros GET
        en PHP             (query string)

        Si categoria = "alabanza"
        URL resultante: php/apiCategorias.php?categoria=alabanza
        
        ¿Por qué forzosamente URL o POST❓
        Porque PHP es un lenguaje del lado del servidor que:
        🟢 No se ejecuta en el navegador (como JavaScript)
        🟢 Solo responde a peticiones HTTP (URLs)
        🟢 Cada ejecución es independiente (stateless)
   */


    // ****<<<<||||****<<<<||||****<<<<||||. CACHE function ****<<<<||||****<<<<||||****<<<<||||****<<<<||||
    // Crear una clave única para esta petición
    const cacheKey = `titles_${categoria}`;
    // Verificar si tenemos datos en caché
    const cachedData = cache.get(cacheKey);
    if (cachedData) {
        console.log('📦 Usando datos de caché');
        return cachedData;
    }
    // Si no hay caché, hacer la petición a la API
    console.log('🌐 Haciendo petición a la API...');
    // ****<<<<||||****<<<<||||****<<<<||||. END CACHE function ****<<<<||||****<<<<||||****<<<<||||****<<<<||||


    const titles = await fetch(`php/apiTitles.php?categoria=${categoria}`); //👉 llamada a la API 🚀


    //Comprobaciones
    console.log('Response completo:', titles);
    console.log('Status:', titles.status);
    console.log('OK:', titles.ok);
    
    if (!titles.ok) {
        throw new Error("Error en la API");
    }

    

    const data = await titles.json();
    
    //Comprobacion 🎯
    console.log('Respuesta completa:', data); 
    console.log('Primer item:', data[0]);
    console.log('Propiedades del primer item:', Object.keys(data[0]));
    
    /*IMPORTANTE: que retorne los datos! si no existe return, NO retorna nada
        ✅ SIN return: La función ejecuta fetch, obtiene datos, pero los deja morir dentro
        ✅ CON return: La función pasa los datos al código que la llamó
        ✅ Todas las funciones (normales, arrow, async) necesitan return para devolver valores
        ✅ Las funciones async devuelven Promises, pero el return determina qué valor contendrá esa
    */

    // Guardar en caché antes de retornar
    cache.set(cacheKey, data);



    return data;
    /*
    Cómo son los datos que retorna?

    */
}


