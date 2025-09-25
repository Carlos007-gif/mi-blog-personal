document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const postsContainer = document.getElementById('postsContainer');
    const originalPosts = Array.from(postsContainer.children);

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();

        if (query === '') {
            // Mostrar todos
            postsContainer.innerHTML = '';
            originalPosts.forEach(post => postsContainer.appendChild(post));
            return;
        }

        // Filtrar
        const filteredPosts = originalPosts.filter(post => {
            const title = post.querySelector('h2 a').textContent.toLowerCase();
            const excerpt = post.querySelector('p').textContent.toLowerCase();
            return title.includes(query) || excerpt.includes(query);
        });

        // Mostrar resultados
        postsContainer.innerHTML = '';
        if (filteredPosts.length === 0) {
            postsContainer.innerHTML = '<p style="text-align:center; padding:20px; color:#7f8c8d;">No se encontraron posts.</p>';
        } else {
            filteredPosts.forEach(post => postsContainer.appendChild(post));
        }
    });
});