export async function getBlogPosts() {
    const response = await fetch('php/apiBlog.php');
    if (!response.ok) {
        throw new Error('Error al obtener los posts del blog');
    }
    const data = await response.json();
    return data;
}