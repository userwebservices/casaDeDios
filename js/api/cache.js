// cache.js - Gestor simple de caché en memoria

class SimpleCache {
    constructor() {
        this.cache = new Map();
        this.defaultTTL = 5 * 60 * 1000; // 5 minutos en milisegundos
    }

    // Guardar datos en caché
    set(key, data, ttl = this.defaultTTL) {
        const expiration = Date.now() + ttl;
        this.cache.set(key, {
            data,
            expiration
        });
        console.log(`✅ Datos guardados en caché para clave: ${key}`);
    }

    // Obtener datos de caché
    get(key) {
        const cached = this.cache.get(key);
        
        if (!cached) {
            console.log(`❌ No hay datos en caché para clave: ${key}`);
            return null;
        }

        // Verificar si ha expirado
        if (Date.now() > cached.expiration) {
            this.cache.delete(key);
            console.log(`⏰ Datos expirados para clave: ${key}`);
            return null;
        }
 
        console.log(`✅ Datos obtenidos de caché para clave: ${key}`);
        return cached.data;
    }

    // Verificar si una clave existe y es válida
    has(key) {
        const cached = this.cache.get(key);
        if (!cached) return false;
        
        if (Date.now() > cached.expiration) {
            this.cache.delete(key);
            return false;
        }
        
        return true;
    }

    // Limpiar caché (opcional)
    clear() {
        this.cache.clear();
        console.log('🧹 Caché limpiado');
    }
}

// Exportar una única instancia
export const cache = new SimpleCache();