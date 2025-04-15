<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Database connection
include '../includes/db_connection.php';

try {
    // Fetch all college adverts from the database, ordered by creation date
    $stmt = $db_conn->prepare("SELECT id, title, created_at FROM college_adverts ORDER BY created_at DESC");
    $stmt->execute();
    $adverts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching adverts: " . $e->getMessage();
} finally {
    $db_conn = null;
}

// Display message if set
$message = isset($_SESSION['advert_message']) ? $_SESSION['advert_message'] : '';
unset($_SESSION['advert_message']);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/picocss/css/pico.pumpkin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة إعلانات الكلية</title>
</head>
<body>
    <main class="container">
        <nav style="display: flex; justify-content: space-between; align-items: center;">
            <a href="dashboard.php" role="button">العودة إلى لوحة التحكم</a>
            <div>
                <a href="create_advert.php" role="button">إضافة إعلان جديد</a>
                <a href="logout.php" role="button">تسجيل الخروج</a>
            </div>
        </nav>
        <br>
        <article>
            <header>
                <h1>إدارة إعلانات الكلية</h1>
            </header>

            <?php if ($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <p style="color:red;"><?php echo $error_message; ?></p>
            <?php elseif (!empty($adverts)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>عنوان الإعلان</th>
                            <th>تاريخ الإنشاء</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($adverts as $advert): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($advert['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($advert['created_at'])); ?></td>
                                <td>
                                    <a href="edit_advert.php?id=<?php echo $advert['id']; ?>" role="button" class="outline">تعديل</a>
                                    <a href="delete_advert.php?id=<?php echo $advert['id']; ?>" role="button" class="secondary outline" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الإعلان؟');">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>لا يوجد أي إعلانات حاليًا.</p>
            <?php endif; ?>
        </article>
    </main>
</body>
</html>