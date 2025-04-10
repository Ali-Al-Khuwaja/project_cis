<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if advert ID is provided in the URL and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $advert_id_to_delete = $_GET['id'];

    // Database connection
    include '../includes/db_connection.php';

    try {
        // Prepare and execute SQL DELETE query
        $stmt = $db_conn->prepare("DELETE FROM college_adverts WHERE id = :id");
        $stmt->execute(['id' => $advert_id_to_delete]);

        // Check if any rows were affected (meaning the advert was deleted)
        if ($stmt->rowCount() > 0) {
            $_SESSION['advert_message'] = '<p style="color:green;">تم حذف الإعلان بنجاح!</p>'; // Arabic: "Advert deleted successfully!"
        } else {
            $_SESSION['advert_message'] = '<p style="color:orange;">لم يتم العثور على الإعلان أو أنه تم حذفه بالفعل.</p>'; // Arabic: "Advert not found or already deleted."
        }

    } catch (PDOException $e) {
        // Database error
        $_SESSION['advert_message'] = '<p style="color:red;">حدث خطأ أثناء حذف الإعلان: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Arabic: "An error occurred while deleting the advert: " . error message
    } finally {
        // Close database connection
        $db_conn = null;
    }
} else {
    // Invalid or missing advert ID in URL
    $_SESSION['advert_message'] = '<p style="color:red;">معرف الإعلان غير صالح.</p>'; // Arabic: "Invalid advert ID."
}

// Redirect back to the manage adverts page
header("Location: manage_adverts.php");
exit();
?>