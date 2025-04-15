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
        $stmt = $db_conn->prepare("SELECT title, content, featured_image FROM posts WHERE id = :id");
        $stmt->execute(['id' => $post_id_to_edit]);
        $post_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post_data) {
            // Post found, pre-fill form fields
            $post_title = $post_data['title'];
            $post_content = $post_data['content'];
        } else {
            // Post not found (invalid ID)
            $post_message = '<p style="color:red;">الخبر غير موجودة أو معرف الخبر غير صالح.</p>'; // Arabic: "Post not found or invalid post ID."
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
    $post_message = '<p style="color:red;">معرف الخبر مفقود أو غير صالح.</p>'; // Arabic: "Post ID is missing or invalid."
}

// Handle form submission for updating the post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id_to_edit']) && is_numeric($_POST['post_id_to_edit'])) {
    // Form submitted for update

    $updated_post_id = $_POST['post_id_to_edit'];
    $updated_post_title = $_POST['post_title'];
    $updated_post_content = $_POST['post_content'];
    $updated_featured_image_path = null; // Initialize

    // Handle Featured Image Upload (similar to create_post.php)
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $image_name = $_FILES['featured_image']['name'];
        $image_tmp_name = $_FILES['featured_image']['tmp_name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $unique_filename = time() . '_' . uniqid() . '.' . $image_extension;
        $destination_path = $upload_dir . $unique_filename;

        if (move_uploaded_file($image_tmp_name, $destination_path)) {
            $updated_featured_image_path = 'uploads/images/' . $unique_filename;
        } else {
            $post_message .= '<p style="color:red;">فشل تحميل الصورة المميزة.</p>'; // Arabic: "Featured image upload failed."
        }
    }

    // Basic validation
    if (empty($updated_post_title) || empty($updated_post_content)) {
        $post_message = '<p style="color:red;">الرجاء إدخال عنوان ومحتوى الخبر.</p>'; // Arabic: "Please enter post title and content."
    } else {
        // Database connection
        include '../includes/db_connection.php';

        try {
            // Prepare the SQL UPDATE statement
            $sql = "UPDATE posts SET title = :title, content = :content";
            $params = [
                ':title' => $updated_post_title,
                ':content' => $updated_post_content,
            ];

            // Add featured image update if a new image was uploaded
            if ($updated_featured_image_path !== null) {
                $sql .= ", featured_image = :featured_image";
                $params[':featured_image'] = $updated_featured_image_path;
            }

            $sql .= " WHERE id = :id";
            $params[':id'] = $updated_post_id;

            $stmt = $db_conn->prepare($sql);
            $stmt->execute($params);

            $_SESSION['post_message'] = '<p style="color:green;">تم تحديث الخبر بنجاح!</p>'; // Arabic: "Post updated successfully!"
            header("Location: manage_posts.php"); // Redirect back to manage posts
            exit();

        } catch (PDOException $e) {
            // Database error during update
            $_SESSION['post_message'] = '<p style="color:red;">حدث خطأ أثناء تحديث الخبر: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Arabic: "An error occurred while updating the post: " . error message
            header("Location: edit_post.php?id=" . $updated_post_id); // Redirect back to edit form with error
            exit();
        } finally {
            $db_conn = null;
        }
    }
}

// ... (The rest of your HTML code remains the same) ...
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
    <title>تعديل الخبر</title>
    <script src="../lib/tinymce/js/tinymce/tinymce.min.js"></script>
</head>
<body>

    <main class="container">
        <article>
            <header>
                <h1>تعديل الخبر</h1>
                <div>
                    <a href="dashboard.php" role="button">لوحة التحكم</a>
                    <a href="logout.php" role="button" >تسجيل الخروج</a>
                </div>
            </header>
            <?php if ($post_message): ?>
                <?php echo $post_message; ?>
            <?php endif; ?>
            <div>
                <form method="post" action="" enctype="multipart/form-data" >
                    <input type="hidden" name="post_id_to_edit" value="<?php echo htmlspecialchars($post_id_to_edit, ENT_QUOTES, 'UTF-8'); ?>">
                    <label for="post_title">عنوان الخبر</label>
                    <input type="text" id="post_title" name="post_title" value="<?php echo htmlspecialchars($post_title, ENT_QUOTES, 'UTF-8'); ?>" required>
                    <label for="featured_image">تغيير الصورة المميزة (اختياري)</label>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">

                    <?php if (!empty($post_title) && !empty($post_content) && !empty($post_data['featured_image'])): ?>
                        <p>الصورة المميزة الحالية:</p>
                        <img src="../<?php echo htmlspecialchars($post_data['featured_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($post_title, ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 200px; height: auto;">
                    <?php endif; ?>

                    <label for="post_content">محتوى الخبر</label>
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