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

                        $posts[] = [
                            'id' => str_replace('.txt', '', $entry),
                            'title' => trim($titleMatch[1] ?? 'Sin título'),
                            'date' => trim($dateMatch[1] ?? 'Sin fecha'),
                            'author' => trim($authorMatch[1] ?? 'Anónimo'),
                            'excerpt' => substr(strip_tags($body), 0, 120) . '...'
                        ];
                    }
                }
            }
            closedir($handle);
        }

        // Mostrar posts
        foreach ($posts as $post) {
            echo '<article class="post-card">';
            echo '<h2><a href="post.php?id=' . $post['id'] . '">' . htmlspecialchars($post['title']) . '</a></h2>';
            echo '<p class="meta">📅 ' . $post['date'] . ' · 👤 ' . htmlspecialchars($post['author']) . '</p>';
            echo '<p>' . htmlspecialchars($post['excerpt']) . '</p>';
            echo '</article>';
        }
        ?>
    </main>

    <script src="js/script.js"></script>
</body>
</html>