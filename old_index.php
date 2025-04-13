<?php
include 'includes/db_connection.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.pumpkin.min.css">    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدونة أخبار الكلية</title> <link rel="stylesheet" href="style.css">
</head>
<body class="container">
    <header>
        <h1>مدونة أخبار الكلية</h1>
    </header>
    
    <section id="latest-news">
        <h2>آخر الأخبار</h2>
        <?php
            try {
                $stmt = $db_conn->prepare("SELECT title FROM posts ORDER BY created_at DESC LIMIT 4");
                $stmt->execute();
                $latest_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Error fetching posts: " . $e->getMessage();
                $latest_posts = [];
            }
        ?>
        <div class="news-posts">
            <?php if (empty($latest_posts)): ?>
                <p>لا توجد أخبار حتى الآن.</p>
            <?php else: ?>
                <?php foreach ($latest_posts as $post): ?>
                    <p><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a href="all_news.php" role="button" id="show-all-news-btn">كل الأخبار</a>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> مدونة أخبار الكلية</p>
    </footer>
</body>
</html>