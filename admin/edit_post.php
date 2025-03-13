<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$post_message = ''; // For messages (success/error)
$post_title = '';   // To store and pre-fill post title in form
$post_content = ''; // To store and pre-fill post content in form
$post_id_to_edit = null; // Variable to store the post ID being edited

// 1. Get Post ID from URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id_to_edit = $_GET['id'];

    // 2. Fetch Post Data from Database
    try {
        include '../includes/db_connection.php';
        $stmt = $db_conn->prepare("SELECT title, content FROM posts WHERE id = :id");
        $stmt->execute(['id' => $post_id_to_edit]);
        $post_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post_data) {
            // Post found, pre-fill form fields
            $post_title = $post_data['title'];
            $post_content = $post_data['content'];
        } else {
            // Post not found (invalid ID)
            $post_message = '<p style="color:red;">المقالة غير موجودة أو معرف المقالة غير صالح.</p>'; // Arabic: "Post not found or invalid post ID."
            $post_id_to_edit = null; // Reset post ID as it's invalid
        }

    } catch (PDOException $e) {
        // Database error
        $post_message = '<p style="color:red;">خطأ في قاعدة البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Arabic: "Database error: " . error message
        $post_id_to_edit = null; // Reset post ID due to error
    } finally {
        $db_conn = null;
    }
} else {
    // No valid post ID in URL
    $post_message = '<p style="color:red;">معرف المقالة مفقود أو غير صالح.</p>'; // Arabic: "Post ID is missing or invalid."
}
// Handle form submission for updating the post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id_to_edit']) && is_numeric($_POST['post_id_to_edit'])) {
    // Form submitted for update

    $updated_post_id = $_POST['post_id_to_edit'];
    $updated_post_title = $_POST['post_title'];
    $updated_post_content = $_POST['post_content'];

    // Basic validation
    if (empty($updated_post_title) || empty($updated_post_content)) {
        $post_message = '<p style="color:red;">الرجاء إدخال عنوان ومحتوى المقالة.</p>'; // Arabic: "Please enter post title and content."
    } else {
        // Database connection
        include '../includes/db_connection.php';

        try {
            // Prepare and execute SQL UPDATE query
            $stmt = $db_conn->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
            $stmt->execute(['title' => $updated_post_title, 'content' => $updated_post_content, 'id' => $updated_post_id]);

            // Check if any rows were affected (meaning the update was successful)
            if ($stmt->rowCount() > 0) {
                $post_message = '<p style="color:green;">تم تحديث المقالة بنجاح!</p>'; // Arabic: "Post updated successfully!"
            } else {
                $post_message = '<p style="color:orange;">لم يتم إجراء أي تغييرات على المقالة.</p>'; // Arabic: "No changes were made to the post." (Could happen if the user didn't modify anything)
            }

            // Optionally, you could redirect back to the dashboard after successful update
            // header("Location: dashboard.php");
            // exit;

        } catch (PDOException $e) {
            // Database error
            $post_message = '<p style="color:red;">حدث خطأ أثناء تحديث المقالة في قاعدة البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Arabic: "An error occurred while updating the post in the database: " . error message
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
    <title>تعديل المقالة</title>
    <script src="../lib/tinymce/js/tinymce/tinymce.min.js"></script>
</head>
<body>

    <main class="container">
        <article>
            <header>
                <h1>تعديل المقالة</h1>
                <div>
                    <a href="logout.php" role="button" >تسجيل الخروج</a>
                </div>
            </header>
            <?php if ($post_message): ?>
                <?php echo $post_message; ?>
            <?php endif; ?>
            <div>
                <form method="post" action="">
                    <input type="hidden" name="post_id_to_edit" value="<?php echo htmlspecialchars($post_id_to_edit, ENT_QUOTES, 'UTF-8'); ?>">
                    <label for="post_title">عنوان المقالة</label>
                    <input type="text" id="post_title" name="post_title" value="<?php echo htmlspecialchars($post_title, ENT_QUOTES, 'UTF-8'); ?>" required>

                    <label for="post_content">محتوى المقالة</label>
                    <textarea id="post_content" name="post_content" rows="10"><?php echo htmlspecialchars($post_content, ENT_QUOTES, 'UTF-8'); ?></textarea>

                    <button type="submit">حفظ التعديلات</button>
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