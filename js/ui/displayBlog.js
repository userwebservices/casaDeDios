export function displayBlogPosts(posts, contenedor) {
    contenedor.innerHTML = '';

    if (posts.length === 0) {
        contenedor.innerHTML = '<p>Aún no hay publicaciones.</p>';
        return;
    }

    posts.forEach(post => {
        const card = document.createElement('article');
        card.classList.add('blog-card');

        const imagen = post.imagen_portada
            ? `<img src="${post.imagen_portada}" alt="${post.titulo}">`
            : '';

        card.innerHTML = `
            ${imagen}
            <span class="blog-card__categoria">${post.categoria ?? ''}</span>
            <h2 class="blog-card__titulo">${post.titulo}</h2>
            <p class="blog-card__extracto">${post.extracto ?? ''}</p>
            <a href="blog-post.php?slug=${post.slug}" class="blog-card__link">Leer más</a>
        `;
        contenedor.appendChild(card);
    });
}