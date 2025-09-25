<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Blog Personal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>📚 Mi Blog Personal</h1>
        <input type="text" id="searchInput" placeholder="Buscar posts...">
        <a href="new-post.php" style="margin-left: 20px; color: #27ae60; font-weight: bold;">➕ Nuevo Post</a>
    </header>

    <main id="postsContainer">
        <?php
        $postsDir = 'posts/';
        $posts = [];

        // Leer todos los archivos .txt
        if ($handle = opendir($postsDir)) {
            while (false !== ($entry = readdir($handle))) {
                if (strpos($entry, '.txt') !== false) {
                    $filePath = $postsDir . $entry;
                    $content = file_get_contents($filePath);
                    $parts = explode("\n---\n", $content, 2);

                    if (count($parts) == 2) {
                        $metadata = $parts[0];
                        $body = $parts[1];

                        preg_match('/Título:\s*(.*)/', $metadata, $titleMatch);
                        preg_match('/Fecha:\s*(.*)/', $metadata, $dateMatch);
                        preg_match('/Autor:\s*(.*)/', $metadata, $authorMatch);
                        preg_match('/Categoría:\s*(.*)/', $metadata, $categoryMatch); // Para mejora 4

                        $posts[] = [
                            'id' => str_replace('.txt', '', $entry),
                            'title' => trim($titleMatch[1] ?? 'Sin título'),
                            'date' => trim($dateMatch[1] ?? 'Sin fecha'),
                            'author' => trim($authorMatch[1] ?? 'Anónimo'),
                            'category' => trim($categoryMatch[1] ?? 'Sin categoría'),
                            'excerpt' => substr(strip_tags($body), 0, 120) . '...'
                        ];
                    }
                }
            }
            closedir($handle);
        }

        // Ordenar por fecha (más reciente primero)
        usort($posts, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        // Obtener categorías únicas
        $categories = [];
        foreach ($posts as $post) {
            $categories[$post['category']] = true;
        }
        $categories = array_keys($categories);

        // Filtro por categoría
        $currentCategory = $_GET['category'] ?? 'all';
        if ($currentCategory !== 'all') {
            $postsToShow = array_filter($postsToShow, function($post) use ($currentCategory) {
                return $post['category'] === $currentCategory;
            });
        }

        // Paginación
        $perPage = 2;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $totalPosts = count($posts);
        $totalPages = ceil($totalPosts / $perPage);
        $start = ($page - 1) * $perPage;
        $postsToShow = array_slice($posts, $start, $perPage);

        // Mostrar posts
        foreach ($postsToShow as $post) {
            echo '<article class="post-card">';
            echo '<h2><a href="post.php?id=' . $post['id'] . '">' . htmlspecialchars($post['title']) . '</a></h2>';
            echo '<p class="meta">📅 ' . $post['date'] . ' · 👤 ' . htmlspecialchars($post['author']) . ' · 🏷️ ' . htmlspecialchars($post['category']) . '</p>';
            echo '<p>' . htmlspecialchars($post['excerpt']) . '</p>';
            echo '</article>';
        }

        // Botones de paginación
        echo '<div class="pagination">';
        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '">← Anterior</a>';
        }
        if ($page < $totalPages) {
            echo '<a href="?page=' . ($page + 1) . '">Siguiente →</a>';
        }
        echo '</div>';
        ?>
    </main>

    <!-- Filtro de categorías -->
    <div class="category-filter">
        <strong>Filtrar por:</strong>
        <a href="index.php?page=1" <?= $currentCategory === 'all' ? 'class="active"' : '' ?>>Todos</a>
        <?php foreach ($categories as $cat): ?>
            <a href="index.php?page=1&category=<?= urlencode($cat) ?>" <?= $currentCategory === $cat ? 'class="active"' : '' ?>>
                <?= htmlspecialchars($cat) ?>
            </a>
        <?php endforeach; ?>
    </div>
    
    <div class="comments-info">
        <p>💬 Comentarios: <span id="commentCount">3</span> (simulados)</p>
    </div>

    <script>
        // Simular conteo de comentarios (podrías guardarlo en sesión o archivo más adelante)
        document.addEventListener('DOMContentLoaded', function() {
            const commentCount = Math.floor(Math.random() * 10) + 1; // 1 a 10
            document.getElementById('commentCount').textContent = commentCount;
        });
    </script>

    <script src="js/script.js"></script>
</body>
</html>