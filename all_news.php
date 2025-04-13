<?php
include 'includes/db_connection.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كل أخبار الكلية</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.pumpkin.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="container">
    <header>
        <h1>كل أخبار الكلية</h1>
        <nav>
            <ul>
                <li><a href="index.php">الصفحة الرئيسية</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="all-news-page">
            <h2>كل الأخبار</h2>
            <?php
            try {
                $stmt = $db_conn->prepare("SELECT title, content, featured_image, created_at FROM posts ORDER BY created_at DESC");
                $stmt->execute();
                $all_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Error fetching posts: " . $e->getMessage();
                $all_posts = [];
            }
            ?>
            <?php if (empty($all_posts)): ?>
                <p>لا توجد أخبار حتى الآن.</p>
            <?php else: ?>
                <div class="all-news-list">
                    <?php foreach ($all_posts as $post): ?>
                        <article>
                            <header>
                                <h3><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <small>تاريخ النشر: <?php echo date('Y-m-d', strtotime($post['created_at'])); ?></small>
                            </header>
                            <?php if (!empty($post['featured_image'])): ?>
                                <img src="<?php echo htmlspecialchars($post['featured_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; height: auto;">
                            <?php else: ?>
                                <img src="assets/images/landscape-placeholder-svgrepo-com.svg" alt="لا توجد صورة" style="max-width: 100%; height: auto;">
                            <?php endif; ?>
                            <p><?php echo substr(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'), 0, 200) . '...'; ?></p>
                            <footer>
                                <a href="#" role="button" class="outline">اقرأ المزيد</a>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> مدونة أخبار الكلية</p>
    </footer>
</body>
</html>