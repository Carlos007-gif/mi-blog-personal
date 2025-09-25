<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');
    $author = $_POST['author'] ?? 'Yo';
    $category = $_POST['category'] ?? 'General';
    $content = $_POST['content'] ?? '';

    if (empty($title) || empty($content)) {
        $error = "Título y contenido son obligatorios.";
    } else {
        // Generar ID único
        $id = 'post' . time();
        $filePath = 'posts/' . $id . '.txt';

        // Escribir archivo
        $fileContent = "Título: $title\nFecha: $date\nAutor: $author\nCategoría: $category\n---\n$content";
        file_put_contents($filePath, $fileContent);

        // Redirigir a la lista
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Post | Mi Blog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>📚 Mi Blog Personal</h1>
        <a href="index.php">← Volver al inicio</a>
    </header>

    <main>
        <h2>➕ Crear Nuevo Post</h2>
        <?php if (isset($error)): ?>
            <div style="color: red; background: #fdd; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="title">Título:</label>
            <input type="text" id="title" name="title" required>

            <label for="date">Fecha (YYYY-MM-DD):</label>
            <input type="text" id="date" name="date" value="<?= date('Y-m-d') ?>" required>

            <label for="author">Autor:</label>
            <input type="text" id="author" name="author" value="Yo" required>

            <label for="category">Categoría:</label>
            <input type="text" id="category" name="category" value="General">

            <label for="content">Contenido:</label>
            <textarea id="content" name="content" rows="10" required></textarea>

            <button type="submit">Guardar Post</button>
        </form>
    </main>
</body>
</html>