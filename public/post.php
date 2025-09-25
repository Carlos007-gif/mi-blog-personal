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
    </main>

    <script src="js/script.js"></script>
</body>
</html>