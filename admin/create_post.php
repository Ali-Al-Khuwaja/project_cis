<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    // 2. Handle Featured Image Upload
    $featured_image_path = null; // Initialize featured image path

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/images/'; // Directory to store uploaded images (create this directory if it doesn't exist)
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create directory if it doesn't exist
        }

        $image_name = $_FILES['featured_image']['name'];
        $image_tmp_name = $_FILES['featured_image']['tmp_name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $unique_filename = time() . '_' . uniqid() . '.' . $image_extension; // Generate unique filename
        $destination_path = $upload_dir . $unique_filename;

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($image_tmp_name, $destination_path)) {
            $featured_image_path = 'uploads/images/' . $unique_filename; // Store relative path in database
        } else {
            $post_message .= '<p style="color:red;">فشل تحميل الصورة المميزة.</p>'; // Arabic: "Featured image upload failed."
        }
    }

    // 3. Basic Validation (as before)
    if (empty($post_title) || empty($post_content)) {
        $post_message = '<p style="color:red;">الرجاء إدخال عنوان ومحتوى المقالة.</p>'; // Arabic: "Please enter post title and content."
    } else {
        // 4. Database Connection (as before)
        include '../includes/db_connection.php';

        try {
            // 5. Prepare and Execute SQL INSERT Query (modified to include featured_image)
            $stmt = $db_conn->prepare("INSERT INTO posts (title, content, featured_image) VALUES (:title, :content, :featured_image)");
            $stmt->execute(['title' => $post_title, 'content' => $post_content, 'featured_image' => $featured_image_path]);

            // Post saved successfully (as before)
            // $post_message = '<p style="color:green;">تم نشر المقالة بنجاح!</p>'; // Remove or comment this out
            $_SESSION['post_message'] = '<p style="color:green;">تم نشر المقالة بنجاح!</p>'; // Store message in session
            header("Location: manage_posts.php"); // Redirect back to the create post page
            exit(); // Ensure no further code is executed after the redirect

            // Optionally, clear the form fields after successful submission (commented out)
            // $_POST['post_title'] = '';
            // $_POST['post_content'] = '';

        } catch (PDOException $e) {
            // Database error (as before)
            $post_message = '<p style="color:red;">حدث خطأ أثناء حفظ المقالة في قاعدة البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>';
        } finally {
            // Close database connection (as before)
            $db_conn = null;
        }
    }
}
// Retrieve the message from the session if it exists
    if (isset($_SESSION['post_message'])) {
    $post_message = $_SESSION['post_message'];
    unset($_SESSION['post_message']); // Clear the session message
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
                <h1>إنشاء خبر جديدة</h1>
                <div>
                    <a href="dashboard.php" role="button" >لوحة التحكم</a>
                    <a href="logout.php" role="button" >تسجيل الخروج</a>
                </div>
            </header>
            <?php if ($post_message): ?>
                <?php echo $post_message; ?>
            <?php endif; ?>
            <div>
                <form method="post" action="" enctype="multipart/form-data">
                    <label for="post_title">عنوان الخبر</label>
                    <input type="text" id="post_title" name="post_title" required>
                    <label for="featured_image">صورة مميزة</label>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">
                    <label for="post_content">محتوى الخبر</label>
                    <textarea id="post_content" name="post_content" rows="10"></textarea>

                    <button type="submit">نشر الخبر</button>
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