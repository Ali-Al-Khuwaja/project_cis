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
if (isset($_SESSION['delete_message'])) {
    echo $_SESSION['delete_message'];
    unset($_SESSION['delete_message']); // Remove the message from the session after displaying it
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

                <div style="display: flex; gap:20px ">
                    <div style="display: flex; gap:20px">
                        <p><a href="manage_posts.php" role="button">إدارة الأخبار</a></p>
                        <p><a href="create_post.php" role="button">إنشاء خبر جديدة</a></p>
                    </div>
                    <div style="display: flex; gap:20px">
                        <p><a href="manage_adverts.php" role="button">إدارة الإعلانات</a></p>
                        <p><a href="create_advert.php" role="button">إنشاء إعلان جديد</a></p>
                    </div>
                </div>

            </div>

        </article>
    </main>

</body>
</html>