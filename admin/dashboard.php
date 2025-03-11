<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Fetch all blog posts from the database for display in admin dashboard
try {
    include '../includes/db_connection.php';
    $stmt = $db_conn->prepare("SELECT id, title, created_at FROM posts ORDER BY created_at DESC");
    $stmt->execute();
    $all_posts_admin = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error fetching posts: " . $e->getMessage();
    $all_posts_admin = []; // Initialize as empty array in case of error
} finally {
    $db_conn = null;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.pumpkin.min.css">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير</title>
</head>
<body>

    <main class="container">
        <article>
            <header>
                <h1>لوحة تحكم المدير</h1>
                <div>
                    <a href="logout.php" role="button" >تسجيل الخروج</a>
                </div>
            </header>

            <div>
                <p>مرحبا بك في لوحة تحكم المدير!</p>
                <p>من هنا يمكنك إدارة مدونة أخبار الكلية.</p>

                <p><a href="create_post.php" role="button">إنشاء مقالة جديدة</a></p>

                <h3>المقالات المنشورة</h3>
                <div class="overflow-auto">
                                    <?php if (empty($all_posts_admin)): ?>
                    <p>لا توجد مقالات منشورة حتى الآن.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>عنوان المقالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>تعديل</th>
                                <th>حذف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_posts_admin as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?></td> <td><a href="#" role="button" class="outline">تعديل</a></td> 
                                <td><a href="#" role="button" class="secondary outline">حذف</a></td> 
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                </div>
            </div>

        </article>
    </main>

</body>
</html>