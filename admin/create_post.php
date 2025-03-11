<?php
session_start(); // Start session (if not already started at the very top of the file)

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$post_message = ''; // Variable for success/error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form submitted

    // 1. Get Post Data from Form
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content']; // TinyMCE content will be here

    // 2. Basic Validation
    if (empty($post_title) || empty($post_content)) {
        $post_message = '<p style="color:red;">الرجاء إدخال عنوان ومحتوى المقالة.</p>'; // Arabic: "Please enter post title and content."
    } else {
        // 3. Database Connection
        include '../includes/db_connection.php';

        try {
            // 4. Prepare and Execute SQL INSERT Query
            $stmt = $db_conn->prepare("INSERT INTO posts (title, content) VALUES (:title, :content)");
            $stmt->execute(['title' => $post_title, 'content' => $post_content]);

            // Post saved successfully
            $post_message = '<p style="color:green;">تم نشر المقالة بنجاح!</p>'; // Arabic: "Post published successfully!"
            // Optionally, clear the form fields after successful submission
            $_POST['post_title'] = '';
            $_POST['post_content'] = '';


        } catch (PDOException $e) {
            // Database error
            $post_message = '<p style="color:red;">حدث خطأ أثناء حفظ المقالة في قاعدة البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Arabic: "An error occurred while saving the post to the database: " . error message
        } finally {
            // Close database connection
            $db_conn = null;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/picocss/css/pico.pumpkin.css">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء مقالة جديدة</title>
    <script src="../lib/tinymce/js/tinymce/tinymce.min.js"></script>
</head>
<body>

    <main class="container">
        <article>
            <header>
                <h1>إنشاء مقالة جديدة</h1>
                <div>
                    <a href="logout.php" role="button" >تسجيل الخروج</a>
                </div>
            </header>
            <?php if ($post_message): ?>
                <?php echo $post_message; ?>
            <?php endif; ?>
            <div>
                <form method="post" action="">
                    <label for="post_title">عنوان المقالة</label>
                    <input type="text" id="post_title" name="post_title" required>

                    <label for="post_content">محتوى المقالة</label>
                    <textarea id="post_content" name="post_content" rows="10"></textarea>

                    <button type="submit">نشر المقالة</button>
                </form>
            </div>

        </article>
        <script>
            tinymce.init({
                selector: '#post_content', // Target the textarea by its ID
                license_key: 'gpl' ,
                directionality: 'rtl',
                skin: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide', // Auto theme based on system preference
                content_css: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default' // Optional: content CSS
            });
        </script>
    </main>

</body>
</html>