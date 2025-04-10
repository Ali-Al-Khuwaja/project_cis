<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$advert_message = ''; // Variable for success/error messages

// Get advert ID from URL if it exists
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $advert_id_to_edit = $_GET['id'];

    // Database connection
    include '../includes/db_connection.php';

    try {
        // Fetch the advert data based on the ID
        $stmt = $db_conn->prepare("SELECT title, content FROM college_adverts WHERE id = :id");
        $stmt->execute(['id' => $advert_id_to_edit]);
        $advert_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($advert_data) {
            $advert_title = $advert_data['title'];
            $advert_content = $advert_data['content'];
        } else {
            // Advert not found (invalid ID)
            $advert_message = '<p style="color:red;">الإعلان غير موجود أو معرف الإعلان غير صالح.</p>'; // Arabic: "Advert not found or invalid advert ID."
            $advert_id_to_edit = null; // Reset advert ID as it's invalid
        }

    } catch (PDOException $e) {
        // Database error
        $advert_message = '<p style="color:red;">خطأ في قاعدة البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Arabic: "Database error: " . error message
        $advert_id_to_edit = null; // Reset advert ID due to error
    } finally {
        $db_conn = null;
    }
} else {
    // Invalid or missing advert ID in URL
    $advert_message = '<p style="color:red;">معرف الإعلان غير صالح.</p>'; // Arabic: "Invalid advert ID."
    $advert_id_to_edit = null;
}

// Handle form submission for updating the advert
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['advert_id_to_edit']) && is_numeric($_POST['advert_id_to_edit'])) {
    $updated_advert_id = $_POST['advert_id_to_edit'];
    $updated_advert_title = $_POST['advert_title'];
    $updated_advert_content = $_POST['advert_content'];

    // Basic validation
    if (empty($updated_advert_title) || empty($updated_advert_content)) {
        $advert_message = '<p style="color:red;">الرجاء إدخال عنوان ومحتوى الإعلان.</p>'; // Arabic: "Please enter advert title and content."
    } else {
        // Database connection
        include '../includes/db_connection.php';

        try {
            // Prepare and execute SQL UPDATE query
            $stmt = $db_conn->prepare("UPDATE college_adverts SET title = :title, content = :content WHERE id = :id");
            $stmt->execute(['title' => $updated_advert_title, 'content' => $updated_advert_content, 'id' => $updated_advert_id]);

            // Check if any rows were affected (meaning the update was successful)
            if ($stmt->rowCount() > 0) {
                $_SESSION['advert_message'] = '<p style="color:green;">تم تحديث الإعلان بنجاح!</p>'; // Arabic: "Advert updated successfully!"
            } else {
                $_SESSION['advert_message'] = '<p style="color:orange;">لم يتم إجراء أي تغييرات على الإعلان.</p>'; // Arabic: "No changes were made to the advert."
            }

            header("Location: edit_advert.php?id=" . $updated_advert_id); // Redirect back to the edit page
            exit;

        } catch (PDOException $e) {
            // Database error
            $advert_message = '<p style="color:red;">حدث خطأ أثناء تحديث الإعلان في قاعدة البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Arabic: "An error occurred while updating the advert in the database: " . error message
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
    <title>تعديل الإعلان</title>
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
                <h1>تعديل الإعلان</h1>
            </header>
            <?php if ($message): ?>
                <?php echo $message; ?>
            <?php endif; ?>
            <?php if ($advert_id_to_edit): ?>
                <div>
                    <form method="post" action="">
                        <input type="hidden" name="advert_id_to_edit" value="<?php echo htmlspecialchars($advert_id_to_edit, ENT_QUOTES, 'UTF-8'); ?>">
                        <label for="advert_title">عنوان الإعلان</label>
                        <input type="text" id="advert_title" name="advert_title" value="<?php echo htmlspecialchars($advert_title, ENT_QUOTES, 'UTF-8'); ?>" required>
                        <label for="advert_content">محتوى الإعلان</label>
                        <textarea id="advert_content" name="advert_content" rows="10"><?php echo htmlspecialchars($advert_content, ENT_QUOTES, 'UTF-8'); ?></textarea>

                        <button type="submit">حفظ التعديلات</button>
                    </form>
                </div>
            <?php elseif ($advert_message): ?>
                <?php echo $advert_message; ?>
            <?php endif; ?>

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