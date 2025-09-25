<?php
$id = $_GET['id'] ?? '';
$filePath = 'posts/' . $id . '.txt';

if (!file_exists($filePath)) {
    header("Location: index.php");
    exit;
}

$content = file_get_contents($filePath);
$parts = explode("\n---\n", $content, 2);

if (count($parts) != 2) {
    die("Post inválido.");
}

$metadata = $parts[0];
$body = $parts[1];

preg_match('/Título:\s*(.*)/', $metadata, $titleMatch);
preg_match('/Fecha:\s*(.*)/', $metadata, $dateMatch);
preg_match('/Autor:\s*(.*)/', $metadata, $authorMatch);

$title = trim($titleMatch[1] ?? 'Sin título');
$date = trim($dateMatch[1] ?? 'Sin fecha');
$author = trim($authorMatch[1] ?? 'Anónimo');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> | Mi Blog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>📚 Mi Blog Personal</h1>
        <a href="index.php">← Volver al inicio</a>
    </header>

    <main class="post-detail">
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <p class="meta">📅 <?php echo $date; ?> · 👤 <?php echo htmlspecialchars($author); ?></p>
        <div class="post-content">
            <?php echo nl2br(htmlspecialchars($body)); ?>
        </div>

        <?php
        // Ruta del archivo de comentarios
        $commentsFile = 'comments/' . $id . '.json';

        // Inicializar array de comentarios
        $comments = [];

        // Si existe el archivo, cargar comentarios
        if (file_exists($commentsFile)) {
            $jsonContent = file_get_contents($commentsFile);
            $comments = json_decode($jsonContent, true) ?: [];
        }

        // Procesar envío de comentario
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['comment'])) {
            $name = trim($_POST['name']);
            $commentText = trim($_POST['comment']);
            $date = date('Y-m-d H:i:s');

            if (!empty($name) && !empty($commentText)) {
                $newComment = [
                    'name' => htmlspecialchars($name),
                    'text' => htmlspecialchars($commentText),
                    'date' => $date
                ];

                $comments[] = $newComment;

                // Guardar en archivo JSON
                file_put_contents($commentsFile, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                // Redirigir para evitar reenvío al recargar
                header("Location: post.php?id=" . $id);
                exit;
            }
        }
        ?>

        <!-- Formulario de comentarios -->
        <section class="comments-section">
            <h3>💬 Deja un comentario</h3>
            <form method="POST" action="">
                <input type="hidden" name="postId" value="<?= $id ?>">
                <label for="commentName">Nombre:</label>
                <input type="text" id="commentName" name="name" required>

                <label for="commentText">Comentario:</label>
                <textarea id="commentText" name="comment" rows="5" required></textarea>

                <button type="submit">Enviar comentario</button>
            </form>
        </section>

        <!-- Mostrar comentarios -->
        <section class="comments-list">
            <h3>🗨️ Comentarios (<?= count($comments) ?>)</h3>
            <?php if (count($comments) === 0): ?>
                <p style="color: #7f8c8d; text-align: center;">Aún no hay comentarios. ¡Sé el primero!</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <strong><?= $comment['name'] ?></strong> <small>(<?= $comment['date'] ?>)</small>
                        <p><?= $comment['text'] ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <script src="js/script.js"></script>
</body>
</html>