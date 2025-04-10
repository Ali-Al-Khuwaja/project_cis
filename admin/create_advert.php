<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$advert_message = ''; // Variable for success/error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form submitted

    // 1. Get Advert Data from Form
    $advert_title = $_POST['advert_title'];
    $advert_content = $_POST['advert_content']; // TinyMCE content will be here

    // 2. Basic Validation
    if (empty($advert_title) || empty($advert_content)) {
        $advert_message = '<p style="color:red;">الرجاء إدخال عنوان ومحتوى الإعلان.</p>'; // Arabic: "Please enter advert title and content."
    } else {
        // 3. Database Connection
        include '../includes/db_connection.php';

        try {
            // 4. Prepare and Execute SQL INSERT Query
            $stmt = $db_conn->prepare("INSERT INTO college_adverts (title, content) VALUES (:title, :content)");
            $stmt->execute(['title' => $advert_title, 'content' => $advert_content]);

            // Advert saved successfully
            $_SESSION['advert_message'] = '<p style="color:green;">تم إضافة الإعلان بنجاح!</p>';
            header("Location: manage_adverts.php"); // Redirect to manage adverts page
            exit();

        } catch (PDOException $e) {
            // Database error
            $advert_message = '<p style="color:red;">حدث خطأ أثناء حفظ الإعلان في قاعدة البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Arabic: "An error occurred while saving the advert to the database: " . error message
        } finally {
            // Close database connection
            $db_conn = null;
        }
    }
}

// Retrieve the message from the session if it exists
$message = isset($_SESSION['advert_message']) ? $_SESSION['advert_message'] : $advert_message;
unset($_SESSION['advert_message']);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/picocss/css/pico.pumpkin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة إعلان جديد</title>
    <script src="../lib/tinymce/js/tinymce/tinymce.min.js"></script>
</head>
<body>
    <main class="container">
        <article>
            <nav aria-label="breadcrumb">
                <ul>
                    <li><a href="dashboard.php">لوحة التحكم</a></li>
                    <li><a href="manage_adverts.php">إدارة إعلانات الكلية</a></li>
                </ul>
            </nav>
            <header>
                <h1>إضافة إعلان جديد</h1>
            </header>
            <?php if ($message): ?>
                <?php echo $message; ?>
            <?php endif; ?>
            <div>
                <form method="post" action="">
                    <label for="advert_title">عنوان الإعلان</label>
                    <input type="text" id="advert_title" name="advert_title" required>
                    <label for="advert_content">محتوى الإعلان</label>
                    <textarea id="advert_content" name="advert_content" rows="10"></textarea>

                    <button type="submit">إضافة الإعلان</button>
                </form>
            </div>

        </article>
        <script>
            tinymce.init({
                selector: '#advert_content', // Target the textarea by its ID
                license_key: 'gpl' ,
                directionality: 'rtl',
                skin: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide', // Auto theme based on system preference
                content_css: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default' // Optional: content CSS
            });
        </script>
    </main>
</body>
</html>