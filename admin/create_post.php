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
            </header>

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