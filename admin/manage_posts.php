<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Fetch all blog posts from the database
try {
    include '../includes/db_connection.php';
    $stmt = $db_conn->prepare("SELECT id, title, created_at FROM posts ORDER BY created_at DESC");
    $stmt->execute();
    $all_posts_admin = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error fetching posts: " . $e->getMessage();
    $all_posts_admin = [];
} finally {
    $db_conn = null;
}

$delete_message = '';
if (isset($_SESSION['delete_message'])) {
    $delete_message = $_SESSION['delete_message'];
    unset($_SESSION['delete_message']);
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.pumpkin.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأخبار</title>
</head>
<body>

    <main class="container">
        <article>
            <header>
                <h1>إدارة الأخبار</h1>
                <div>
                    <a href="dashboard.php" role="button">لوحة التحكم</a>
                    <a href="logout.php" role="button" >تسجيل الخروج</a>
                </div>
            </header>

            <div>
                <p><a href="create_post.php" role="button">إنشاء خبر جديد</a></p>

                <h3>الأخبار المنشورة</h3>
                <div class="overflow-auto">
                    <?php if ($delete_message): ?>
                        <p><?php echo $delete_message; ?></p>
                    <?php endif; ?>
                    <?php if (empty($all_posts_admin)): ?>
                        <p>لا توجد أخبار منشورة حتى الآن.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>عنوان الخبر</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>تعديل</th>
                                    <th>حذف</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_posts_admin as $post): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?></td>
                                    <td><a href="edit_post.php?id=<?php echo $post['id']; ?>" role="button" class="outline">تعديل</a></td>
                                    <td><a href="delete_post.php?id=<?php echo $post['id']; ?>" role="button" class="secondary outline" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذه الخبر؟');">حذف</a></td>
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