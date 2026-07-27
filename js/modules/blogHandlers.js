import { getBlogPosts } from '../api/apiBlog.js';
import { displayBlogPosts } from '../ui/displayBlog.js';

export async function initBlog() {
    const contenedor = document.querySelector('#blog-lista');

    if (contenedor) {
        try {
            const posts = await getBlogPosts();
            displayBlogPosts(posts, contenedor);
        } catch (error) {
            console.error('Error cargando el blog:', error);
            contenedor.innerHTML = '<p>Hubo un problema al cargar las publicaciones.</p>';
        }
    }

    // Formulario de suscripción
    const formSuscripcion = document.querySelector('#form-suscripcion');
    if (formSuscripcion) {
        formSuscripcion.addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.querySelector('#email-suscripcion').value;
            const mensaje = document.querySelector('#mensaje-suscripcion');

            try {
                const res = await fetch('php/apiSuscripcion.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email })
                });
                const data = await res.json();
                mensaje.textContent = data.mensaje || data.error;
                if (data.success) formSuscripcion.reset();
            } catch (error) {
                mensaje.textContent = 'Error al conectar con el servidor.';
            }
        });
    }
}