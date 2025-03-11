<?php
session_start(); // Start session

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
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
                <p>سيتم هنا إضافة وظائف إدارة المدونة.</p>
                <p><a href="create_post.php" role="button">إنشاء مقالة جديدة</a></p>
            </div>

        </article>
    </main>

</body>
</html>