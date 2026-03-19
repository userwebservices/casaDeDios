


import { cache } from './cache.js';


export async function getLyrics(id) {

    
    
    // ****<<<<||||****<<<<||||****<<<<||||. CACHE function ****<<<<||||****<<<<||||****<<<<||||****<<<<||||
    // Crear una clave única para esta petición
    const cacheKey = `lyrics_${id}`;
    // Verificar si tenemos datos en caché
    const cachedData = cache.get(cacheKey);
    if (cachedData) { 
        console.log('📦 Usando datos de caché');
        return cachedData; 
    }
    // Si no hay caché, hacer la petición a la API
    console.log('🌐 Haciendo petición a la API...');
    // ****<<<<||||****<<<<||||****<<<<|||| END CACHE function ****<<<<||||****<<<<||||****<<<<||||****<<<<||||



    const response = await fetch(`php/apiCancion.php?id=${id}`);
    if (!response.ok) {
        throw new Error("Error en la API");
    }
    const data = await response.json();
    
    // Guardar en caché antes de retornar
    cache.set(cacheKey, data);

    
    return data;
}