<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id_to_delete = $_GET['id'];

    // Database connection
    include '../includes/db_connection.php';

    try {
        // Prepare and execute SQL DELETE query
        $stmt = $db_conn->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute(['id' => $post_id_to_delete]);

        // Check if any rows were affected (meaning the deletion was successful)
        if ($stmt->rowCount() > 0) {
            $delete_message = '<p style="color:green;">تم حذف المقالة بنجاح!</p>'; // Arabic: "Post deleted successfully!"
        } else {
            $delete_message = '<p style="color:orange;">لم يتم العثور على المقالة أو لا يمكن حذفها.</p>'; // Arabic: "Post not found or could not be deleted."
        }

    } catch (PDOException $e) {
        // Database error
        $delete_message = '<p style="color:red;">حدث خطأ أثناء حذف المقالة من قاعدة البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Arabic: "An error occurred while deleting the post from the database: " . error message
    } finally {
        // Close database connection
        $db_conn = null;
    }

    // Redirect back to the dashboard with a message
    $_SESSION['delete_message'] = $delete_message; // Store message in session to display on dashboard
    header("Location: dashboard.php");
    exit;

} else {
    // Invalid or missing post ID
    $_SESSION['delete_message'] = '<p style="color:red;">معرف المقالة غير صالح للحذف.</p>'; // Arabic: "Invalid post ID for deletion."
    header("Location: dashboard.php");
    exit;
}
?>