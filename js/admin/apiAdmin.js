const AdminAPI = {
    baseURL: '../../php/apiAdmin.php',
    
    async obtenerTodos() {
        try {
            const response = await fetch(`${this.baseURL}?action=getAll`);
            return await response.json();
        } catch (error) {
            throw new Error('Error al obtener cantos: ' + error);
        }
    },

    async obtenerCategorias() {
    try {
        const response = await fetch(`${this.baseURL}?action=getCategorias`);
        return await response.json();
    } catch (error) {
        throw new Error('Error al obtener categorías: ' + error);
    }
    },
    
    async obtenerCanto(id) {
        try {
            const response = await fetch(`${this.baseURL}?action=getOne&id=${id}`);
            return await response.json();
        } catch (error) {
            throw new Error('Error al obtener canto: ' + error);
        }
    },
    
    async crearCanto(data) {
        try {
            const response = await fetch(this.baseURL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'create', ...data })
            });
            return await response.json();
        } catch (error) {
            throw new Error('Error al crear canto: ' + error);
        }
    },
    
    async actualizarCanto(data) {
        try {
            const response = await fetch(this.baseURL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'update', ...data })
            });
            return await response.json();
        } catch (error) {
            throw new Error('Error al actualizar canto: ' + error);
        }
    },
    
    async eliminarCanto(id) {
        try {
            const response = await fetch(`${this.baseURL}?action=delete&id=${id}`, {
                method: 'DELETE'
            });
            return await response.json();
        } catch (error) {
            throw new Error('Error al eliminar canto: ' + error);
        }
    },
    // Funciones para Categorías
async obtenerTodasCategorias() {
    try {
        const response = await fetch(`${this.baseURL}?action=getAllCategorias`);
        return await response.json();
    } catch (error) {
        throw new Error('Error al obtener categorías: ' + error);
    }
},

async obtenerCategoria(id) {
    try {
        const response = await fetch(`${this.baseURL}?action=getOneCategoria&id=${id}`);
        return await response.json();
    } catch (error) {
        throw new Error('Error al obtener categoría: ' + error);
    }
},

async crearCategoria(data) {
    try {
        const response = await fetch(this.baseURL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'createCategoria', ...data })
        });
        return await response.json();
    } catch (error) {
        throw new Error('Error al crear categoría: ' + error);
    }
},

async actualizarCategoria(data) {
    try {
        const response = await fetch(this.baseURL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'updateCategoria', ...data })
        });
        return await response.json();
    } catch (error) {
        throw new Error('Error al actualizar categoría: ' + error);
    }
},

async eliminarCategoria(id) {
    try {
        const response = await fetch(`${this.baseURL}?action=deleteCategoria&id=${id}`, {
            method: 'DELETE'
        });
        return await response.json();
    } catch (error) {
        throw new Error('Error al eliminar categoría: ' + error);
    }
},




// Funciones para Hero Config
async obtenerTodosHeroConfig() {
    try {
        const response = await fetch(`${this.baseURL}?action=getAllHeroConfig`);
        return await response.json();
    } catch (error) {
        throw new Error('Error al obtener configuraciones: ' + error);
    }
},

async obtenerHeroConfig(id) {
    try {
        const response = await fetch(`${this.baseURL}?action=getOneHeroConfig&id=${id}`);
        return await response.json();
    } catch (error) {
        throw new Error('Error al obtener configuración: ' + error);
    }
},

async crearHeroConfig(data) {
    try {
        const response = await fetch(this.baseURL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'createHeroConfig', ...data })
        });
        return await response.json();
    } catch (error) {
        throw new Error('Error al crear configuración: ' + error);
    }
},

async actualizarHeroConfig(data) {
    try {
        const response = await fetch(this.baseURL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'updateHeroConfig', ...data })
        });
        return await response.json();
    } catch (error) {
        throw new Error('Error al actualizar configuración: ' + error);
    }
},

async activarHeroConfig(id) {
    try {
        const response = await fetch(this.baseURL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'activateHeroConfig', id: id })
        });
        return await response.json();
    } catch (error) {
        throw new Error('Error al activar configuración: ' + error);
    }
},

async eliminarHeroConfig(id) {
    try {
        const response = await fetch(`${this.baseURL}?action=deleteHeroConfig&id=${id}`, {
            method: 'DELETE'
        });
        return await response.json();
    } catch (error) {
        throw new Error('Error al eliminar configuración: ' + error);
    }
}

// FIN Funciones para Hero Config


};