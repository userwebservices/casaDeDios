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
            ? `<img src="${post.imagen_portada}" alt="${post.titulo}" class="blog-card__img">`
            : '<div class="blog-card__img blog-card__img--placeholder"></div>';

        const fecha = post.fecha_publicacion
            ? new Date(post.fecha_publicacion).toLocaleDateString('es-MX', { year: 'numeric', month: 'long', day: 'numeric' })
            : '';

        card.innerHTML = `
            <div class="blog-card__media">${imagen}</div>
            <div class="blog-card__content">
                <h2 class="blog-card__titulo">${post.titulo}</h2>
                <p class="blog-card__fecha">${fecha}</p>
                <p class="blog-card__extracto">${post.extracto ?? ''}</p>
                <a href="blog-post.php?slug=${post.slug}" class="blog-card__link">
                    Leer más <span class="blog-card__arrow">&rarr;</span>
                </a>
            </div>
        `;

        contenedor.appendChild(card);
    });
}