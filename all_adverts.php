<?php
include 'includes/db_connection.php';

try {
    $stmt = $db_conn->prepare("SELECT title, content, created_at FROM college_adverts ORDER BY created_at DESC");
    $stmt->execute();
    $all_adverts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Error fetching adverts: " . $e->getMessage();
    $all_adverts = [];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.pumpkin.min.css">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كل إعلانات الكلية</title>
</head>
<body class="container">
    <header>
        <h1>كل إعلانات الكلية</h1>
        <nav>
            <ul>
                <li><a href="index.php">الصفحة الرئيسية</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (isset($error_message)): ?>
            <p style="color:red;"><?php echo $error_message; ?></p>
        <?php elseif (!empty($all_adverts)): ?>
            <div class="all-adverts">
                <?php foreach ($all_adverts as $advert): ?>
                    <article>
                        <h3><?php echo htmlspecialchars($advert['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="advert-date">تاريخ النشر: <?php echo date('Y-m-d', strtotime($advert['created_at'])); ?></p>
                        <div><?php echo nl2br(htmlspecialchars($advert['content'], ENT_QUOTES, 'UTF-8')); ?></div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>لا توجد إعلانات حاليًا.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> موقع الكلية</p>
    </footer>
</body>
</html>