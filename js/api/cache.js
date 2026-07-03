// cache.js - Caché en memoria + persistencia en sessionStorage
class SimpleCache {
    constructor() {
        this.cache = new Map();
        this.defaultTTL = 5 * 60 * 1000; // 5 minutos
        this.storageKey = 'himnario_cache';
        this._loadFromStorage();
    }

    set(key, data, ttl = this.defaultTTL) {
        const expiration = Date.now() + ttl;
        this.cache.set(key, { data, expiration });
        this._saveToStorage();
    }

    get(key) {
        const cached = this.cache.get(key);
        if (!cached) return null;
        if (Date.now() > cached.expiration) {
            this.cache.delete(key);
            this._saveToStorage();
            return null;
        }
        return cached.data;
    }

    has(key) {
        const cached = this.cache.get(key);
        if (!cached) return false;
        if (Date.now() > cached.expiration) {
            this.cache.delete(key);
            return false;
        }
        return true;
    }

    clear() {
        this.cache.clear();
        sessionStorage.removeItem(this.storageKey);
    }

    // --- Persistencia ---
    _saveToStorage() {
        try {
            const obj = Object.fromEntries(this.cache);
            sessionStorage.setItem(this.storageKey, JSON.stringify(obj));
        } catch (e) {
            console.warn('No se pudo guardar caché en sessionStorage:', e);
        }
    }

    _loadFromStorage() {
        try {
            const raw = sessionStorage.getItem(this.storageKey);
            if (!raw) return;
            const obj = JSON.parse(raw);
            const now = Date.now();
            for (const [key, value] of Object.entries(obj)) {
                if (value.expiration > now) {
                    this.cache.set(key, value);
                }
            }
        } catch (e) {
            console.warn('No se pudo cargar caché de sessionStorage:', e);
        }
    }
}

export const cache = new SimpleCache();