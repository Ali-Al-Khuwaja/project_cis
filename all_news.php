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
    
    <section id="all-news-page">
        <h2>كل الأخبار</h2>
        <?php
        try {
            $stmt = $db_conn->prepare("SELECT title FROM posts ORDER BY created_at DESC");
            $stmt->execute();
            $all_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error fetching posts: " . $e->getMessage();
            $all_posts = [];
        }
        ?>
        <div class="news-posts">
            <?php if (empty($all_posts)): ?>
                <p>لا توجد أخبار حتى الآن.</p>
            <?php else: ?>
                <?php foreach ($all_posts as $post): ?>
                    <p><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button id="show-all-news-btn">كل الأخبار</button>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> مدونة أخبار الكلية</p>
    </footer>
</body>
</html>