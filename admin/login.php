<?php
session_start();

$login_error = ''; // Variable to store login error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form submitted

    // 1. Get User Input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 2. Basic Validation 
    if (empty($username) || empty($password)) {
        $login_error = "يرجى إدخال اسم المستخدم وكلمة المرور.";
    } else {
        // 3. Database Connection (Include database connection file)
        include '../includes/db_connection.php';

        try {
            // 4. Database Query - Fetch user from database based on username
            $stmt = $db_conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // User found

                // 5. Password Verification 
                if (password_verify($password, $user['password'])) {
                    // Login successful!

                    // Start session and store user info (e.g., username) in session
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['logged_in'] = true; // Flag to indicate user is logged in

                    // Redirect to admin dashboard
                    header("Location: index.php");
                    exit; // Terminate script execution after redirect
                } else {
                    // Password incorrect
                    $login_error = "كلمة المرور غير صحيحة."; 
                }
            } else {
                // User not found
                $login_error = "اسم المستخدم غير موجود."; 
            }

        } catch (PDOException $e) {
            // Database error
            $login_error = "خطأ في قاعدة البيانات: " . $e->getMessage(); // Arabic: "Database error: " . error message
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.pumpkin.min.css">    
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المدير</title> 
</head>
<body>

    <main class="container">
        <article>
            <?php if ($login_error): ?>
                <p role="alert" style="color: red;"> <?php echo htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <header>
                <h1>تسجيل دخول المدير</h1>
            </header>

        <form method="post" action="">
            <label for="username">اسم المستخدم</label>
            <input type="text" id="username" name="username" required>

            <label for="password">كلمة المرور</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">تسجيل الدخول</button>
        </form>

        </article>
    </main>

</body>
</html>