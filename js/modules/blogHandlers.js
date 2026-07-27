import { getBlogPosts } from '../api/apiBlog.js';
import { displayBlogPosts } from '../ui/displayBlog.js';

export async function initBlog() {
    const contenedor = document.querySelector('#blog-lista');

    if (!contenedor) return; // por si este archivo se carga en una página sin el contenedor

    try {
        const posts = await getBlogPosts();
        displayBlogPosts(posts, contenedor);
    } catch (error) {
        console.error('Error cargando el blog:', error);
        contenedor.innerHTML = '<p>Hubo un problema al cargar las publicaciones.</p>';
    }
}