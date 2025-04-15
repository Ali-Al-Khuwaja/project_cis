<?php
include 'includes/db_connection.php';

try {
    $stmt = $db_conn->prepare("SELECT title, content, featured_image, created_at FROM posts ORDER BY created_at DESC LIMIT 4");
    $stmt->execute();
    $latest_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error fetching posts: " . $e->getMessage();
    $latest_posts = [];
}

try {
    $stmt_adverts = $db_conn->prepare("SELECT title, created_at FROM college_adverts ORDER BY created_at DESC LIMIT 3");
    $stmt_adverts->execute();
    $latest_adverts = $stmt_adverts->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error fetching adverts: " . $e->getMessage();
    $latest_adverts = [];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/landing_page.css">
    <link rel="stylesheet" href="assets/css/typography.css">
    <title>كلية الدراسات المتوسطة - جامعة الأزهر</title>
    <link rel="icon" href="assets/images/logocis.png" type="image/x-icon">
</head>

<body>
    <header class="header-v1">
        <nav class="nav-v1">
            <div class="nav-top-part">
                <div class="cis-logo">
                    <img src="assets/images/logocis.png" alt="">
                    <div class="cis-name">
                        <p>كلية الدراسات المتوسطة - الأزهر</p>
                        <p>College Of Intermediate Studies - Alazhar</p>
                    </div>                  
                </div>
                <div class="nav-icons">
                    <img src="assets/images/map-pin-area-fill.svg" alt="">
                </div>
                <div class="burger-menu-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                </div>  
            </div>
            <div class="burger-menu ">
                <div>
                    <details class="dropdown">
                        <summary> حول الكلية</summary>
                        <ul>
                            <li><a href="html/about/about-collage.html">عن الكلية</a></li>
                            <li><a href="html/about/collage-dean.html">عميد الكلية</a></li>
                            <li><a href="html/about/chairman-of-the-board-of-trustees.html">رئيس مجلس الأمناء</a></li>
                            <li><a href="html/about/board-of-trustees-members.html">أعضاء مجلس الأمناء</a></li>
                            <li><a href="html/about/college-council.html">مجلس الكلية</a></li>

                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> الأقسام و التخصصات</summary>
                        <ul>
                            <li><a href="html/departments-and-specializations/health-professions.html">المهن الصحية</a></li>
                            <li><a href="html/departments-and-specializations/management-professions.html">العلوم الإدارية</a></li>
                            <li><a href="html/departments-and-specializations/computer-professions.html">الهندسة وتكنولوجيا المعلومات</a></li>
                            <li><a href="html/departments-and-specializations/media-professions.html">الإعلام</a></li>
                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> دوائر الكلية</summary>
                        <ul>
                            <li><a href="html/college-departments/academic-affairs.html">الشؤون الأكاديمية</a></li>
                            <li><a href="html/college-departments/administrative-and-financial-affairs.html">الشؤون الإدارية و المالية</a></li>
                            <li><a href="html/college-departments/admission-and-registration.html">القبول والتسجيل</a></li>
                            <li><a href="html/college-departments/student-affairs.html">شؤون الطلبة</a></li>
                            <li><a href="html/college-departments/financial-affairs.html">الشؤون المالية</a></li>
                            <li><a href="html/college-departments/technology-affairs.html">تكنولوجيا المعلومات</a></li>
                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> الإرشادات</summary>
                        <ul>
                            <li><a href="html/guidances/academic-system.html">النظام الأكاديمي</a></li>
                            <li><a href="html/guidances/grants-and-loans.html">المنح والقروض</a></li>
                            <li><a href="html/guidances/terms-and-definitions.html">مصطلحات وتعريفات</a></li>
                            <li><a href="html/guidances/academic-discipline.html">الضبط الأكاديمي</a></li>
                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> الخدمات الإلكترونية</summary>
                        <ul>
                            <li><a href="#">نظام الوظائف الشاغرة</a></li>
                            <li><a href="https://moodle.cis.edu.ps/">التعليم الإلكتروني</a></li>
                            <li><a href="https://www.flickr.com/photos/azharcis/">إحتفالات التخرج</a></li>
                            <li><a href="mailto:Cis@Alazhar.Edu.Ps?subject=Inquiry&body=Hello,">البريد الإلكتروني</a></li>
                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> البوابات</summary>
                        <ul>
                            <li><a href="https://gate.cis.edu.ps/emps/">بوابة المحاضر</a></li>
                            <li><a href="https://gate.cis.edu.ps/student/Slogin.aspx">بوابة الطالب</a></li>
                            <li><a href="https://gate.cis.edu.ps/demps/Elogin.aspx">بوابة الموظف</a></li>
                            <li><a href="https://www.cis.edu.ps/training">التدريب الميداني</a></li>
                        </ul>
                    </details>
                    <a id="openDialog">إتصل بنا</a>

                    <dialog id="contactDialog">
                        <button class="close-btn" id="closeDialog">✖</button>
                        <div class="dialog-header">معلومات الاتصال</div>
                        <div class="contact-info">
                            <p><strong>هاتف:</strong> 2641895 9708</p>
                            <p><strong>البريد الإلكتروني:</strong> Cis@Alazhar.Edu.Ps</p>
                            <p><strong>العنوان:</strong> غزة شارع جمال عبد الناصر</p>
                        </div>
                    </dialog>
                </div>
            </div>
            <div class="nav-bottom-part">
                                <div>
                    <details class="dropdown">
                        <summary> حول الكلية</summary>
                        <ul>
                            <li><a href="html/about/about-collage.html">عن الكلية</a></li>
                            <li><a href="html/about/collage-dean.html">عميد الكلية</a></li>
                            <li><a href="html/about/chairman-of-the-board-of-trustees.html">رئيس مجلس الأمناء</a></li>
                            <li><a href="html/about/board-of-trustees-members.html">أعضاء مجلس الأمناء</a></li>
                            <li><a href="html/about/college-council.html">مجلس الكلية</a></li>

                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> الأقسام و التخصصات</summary>
                        <ul>
                            <li><a href="html/departments-and-specializations/health-professions.html">المهن الصحية</a></li>
                            <li><a href="html/departments-and-specializations/management-professions.html">العلوم الإدارية</a></li>
                            <li><a href="html/departments-and-specializations/computer-professions.html">الهندسة وتكنولوجيا المعلومات</a></li>
                            <li><a href="html/departments-and-specializations/media-professions.html">الإعلام</a></li>
                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> دوائر الكلية</summary>
                        <ul>
                            <li><a href="html/college-departments/academic-affairs.html">الشؤون الأكاديمية</a></li>
                            <li><a href="html/college-departments/administrative-and-financial-affairs.html">الشؤون الإدارية و المالية</a></li>
                            <li><a href="html/college-departments/admission-and-registration.html">القبول والتسجيل</a></li>
                            <li><a href="html/college-departments/student-affairs.html">شؤون الطلبة</a></li>
                            <li><a href="html/college-departments/financial-affairs.html">الشؤون المالية</a></li>
                            <li><a href="html/college-departments/technology-affairs.html">تكنولوجيا المعلومات</a></li>
                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> الإرشادات</summary>
                        <ul>
                            <li><a href="html/guidances/academic-system.html">النظام الأكاديمي</a></li>
                            <li><a href="html/guidances/grants-and-loans.html">المنح والقروض</a></li>
                            <li><a href="html/guidances/terms-and-definitions.html">مصطلحات وتعريفات</a></li>
                            <li><a href="html/guidances/academic-discipline.html">الضبط الأكاديمي</a></li>
                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> الخدمات الإلكترونية</summary>
                        <ul>
                            <li><a href="#">نظام الوظائف الشاغرة</a></li>
                            <li><a href="https://moodle.cis.edu.ps/">التعليم الإلكتروني</a></li>
                            <li><a href="https://www.flickr.com/photos/azharcis/">إحتفالات التخرج</a></li>
                            <li><a href="mailto:Cis@Alazhar.Edu.Ps?subject=Inquiry&body=Hello,">البريد الإلكتروني</a></li>
                        </ul>
                    </details>
                    <details class="dropdown">
                        <summary> البوابات</summary>
                        <ul>
                            <li><a href="https://gate.cis.edu.ps/emps/">بوابة المحاضر</a></li>
                            <li><a href="https://gate.cis.edu.ps/student/Slogin.aspx">بوابة الطالب</a></li>
                            <li><a href="https://gate.cis.edu.ps/demps/Elogin.aspx">بوابة الموظف</a></li>
                            <li><a href="https://www.cis.edu.ps/training">التدريب الميداني</a></li>
                        </ul>
                    </details>
                    <a id="openDialog">إتصل بنا</a>

                    <dialog id="contactDialog">
                        <button class="close-btn" id="closeDialog">✖</button>
                        <div class="dialog-header">معلومات الاتصال</div>
                        <div class="contact-info">
                            <p><strong>هاتف:</strong> 2641895 9708</p>
                            <p><strong>البريد الإلكتروني:</strong> Cis@Alazhar.Edu.Ps</p>
                            <p><strong>العنوان:</strong> غزة شارع جمال عبد الناصر</p>
                        </div>
                    </dialog>
                </div>
            </div>
        </nav>
        <div class="banner">
            <img src="assets/images/cis.jpg" alt="" id="landing-img">
            <div class="gradient-overlay"></div>
            <h2 class="floating-text">تعلن كلية الدراسات المتوسطة - الأزهر <br>بدء العام الدراسي الأول 2025 - 2026</h2>
        </div>
        <div class="grid-links">
            <div class="grid-button">
                <p>العلاقات الخارجية</p>
            </div>
            <div class="grid-button">
                <p><a href="html/college-departments/admission-and-registration.html">القبول والتسجيل</a></p>
            </div>
            <div class="grid-button">
                <p><a href="html/guidances/student-life.html">الحياة الجامعية</a></p>
            </div>
            <div class="grid-button">
                <p><a href="href="mailto:Cis@Alazhar.Edu.Ps?subject=Inquiry&body=Hello,"">البريد الإلكتروني</a></p>
            </div>
            <div class="grid-button">
                <p>التقويم الأكاديمي</p>
            </div>
            <div class="grid-button-blue">
                <p><a href="https://moodle.cis.edu.ps/">التعليم الإلكتروني</a></p>
            </div>
        </div>
    </header>
    <main>
        <div class="stats_section">
            <div class="number_stats">
                <img src="assets/images/student.svg" alt="">
                <p>1503</p>
                <p>طالب نظامي</p>
            </div>
            <div class="number_stats">
                <img src="assets/images/chalkboard-teacher.svg" alt="">
                <p>150</p>
                <p>محاضر أكاديمي</p>
            </div>
            <div class="number_stats">
                <img src="assets/images/certificate.svg" alt="">
                <p>22</p>
                <p>برنامج أكاديمي معتمد</p>
            </div>
            <div class="number_stats">
                <img src="assets/images/graduation-cap.svg" alt="">
                <p>9689</p>
                <p>طالب خريج</p>
            </div>
        </div>
        <div class="news-section">
        <div class="right-side">
            <h1 class="blue">الأخبار</h1>
            <p><?php if (!empty($latest_posts[0]['title'])) { echo htmlspecialchars($latest_posts[0]['title'], ENT_QUOTES, 'UTF-8'); } else { echo 'آخر الأخبار'; } ?></p>
            <p class="post-date"><?php if (!empty($latest_posts[0]['created_at'])) { echo date('Y-m-d', strtotime($latest_posts[0]['created_at'])); } ?></p>
            <div class="contain-post">
                <img src="<?php if (!empty($latest_posts[0]['featured_image'])) { echo htmlspecialchars($latest_posts[0]['featured_image'], ENT_QUOTES, 'UTF-8'); } else { echo '../assets/images/landscape-placeholder-svgrepo-com.svg'; } ?>" alt="<?php if (!empty($latest_posts[0]['title'])) { echo htmlspecialchars($latest_posts[0]['title'], ENT_QUOTES, 'UTF-8'); } else { echo 'آخر الأخبار'; } ?>">
            </div>
        </div>
        <div class="left-side">
            <!-- <div class="news-icons">
                <img src="assets/images/landscape-placeholder-svgrepo-com.svg" alt="">
                <img src="assets/images/landscape-placeholder-svgrepo-com.svg" alt="">
                <img src="assets/images/landscape-placeholder-svgrepo-com.svg" alt="">
                <img src="assets/images/landscape-placeholder-svgrepo-com.svg" alt="">
            </div> -->
            <a href="all_news.php" role="button" id="show-all-news-btn">كل الأخبار</a>
            <div class="last-three-container">
                <?php for ($i = 1; $i < 4; $i++): ?>
                    <?php if (isset($latest_posts[$i]['title'])): ?>
                        <div class="last-three">
                            <img src="<?php if (!empty($latest_posts[$i]['featured_image'])) { echo htmlspecialchars($latest_posts[$i]['featured_image'], ENT_QUOTES, 'UTF-8'); } else { echo '../assets/images/landscape-placeholder-svgrepo-com.svg'; } ?>" alt="<?php echo htmlspecialchars($latest_posts[$i]['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div>
                            <p><?php echo htmlspecialchars($latest_posts[$i]['title'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="post-date"><?php if (!empty($latest_posts[$i]['created_at'])) { echo date('Y-m-d', strtotime($latest_posts[$i]['created_at'])); } ?></p>
                        </div>
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
        </div>
        <div class="announcements-section">
            <div>
                <p class="blue">الإعلانات</p>
                        <a href="all_adverts.php" role="button" id="show-all-adverts-btn">+الكل</a>
            </div>
            <div>
                <?php if (!empty($latest_adverts)): ?>
                    <?php foreach ($latest_adverts as $advert): ?>
                        <div class="ad">
                            <p><?php echo htmlspecialchars($advert['title'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="advert-date"><?php echo date('Y-m-d', strtotime($advert['created_at'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>لا توجد إعلانات حاليًا.</p>
                <?php endif; ?>
            </div>
            
        </div>
            <div class="majors">
                <h2 class="blue">الأقسام الأكاديمية</h2>
                <div class="majors-grid">
                    <div class="majors-card">
                        <img src="assets/images/nursing (1).png" alt="">
                        <p style="color: #0BD99A;">المهن الصحية</p>
                        <p>تعليم صحي متفوق</p>
                    </div>
                    <div class="majors-card">
                        <img src="assets/images/computer.png" alt="">
                        <p style="color: #3F4F9A;">الهندسة و تكنولوجيا المعلومات</p>
                        <p>نطور الحاضر</p>
                    </div>
                    <div class="majors-card">
                        <img src="assets/images/analitics.png" alt="">
                        <p style="color: #E3A730;">العلوم الإدارية والمالية</p>
                        <p>ريادة و إبداع</p>
                    </div>
                    <div class="majors-card">
                        <img src="assets/images/camera.png" alt="">
                        <p style="color: #C20809;">الإعلام</p>
                        <p>ننشر الحقيقة</p>
                    </div>
                </div>
            </div>
        <div style="background-color: #F4F5F6;">
            <div class="showcase">
                <div class="showcase-card">
                <h4 class="title blue" >فيسبوك</h4>
                    <div id="facebook">
                    <div id="fb-root"></div>
                    <script async defer crossorigin="anonymous"
                    src="https://connect.facebook.net/ar_AR/sdk.js#xfbml=1&version=v20.0" nonce="Rw59KAN4"></script>
                    <div class="fb-page " data-href="https://www.facebook.com/AlazharCis" data-tabs="timeline"
                    data-width="400" data-height="" data-small-header="true" data-adapt-container-width="true"
                    data-hide-cover="false" data-show-facepile="false">
                    <blockquote cite="https://www.facebook.com/AlazharCis" class="fb-xfbml-parse-ignore"><a
                    href="https://www.facebook.com/AlazharCis">‏كلية الدراسات المتوسطة - جامعة الأزهر‏</a>
                    </blockquote>
                </div>
            </div>

            </div>
            <div class="showcase-card">
                <h4 class="title blue">معرض صور التخرج</h4>
                <div class="flexing-grid">
                    <img src="assets/images/grad1.avif" alt="">
                    <img src="assets/images/grad2.avif" alt="">
                    <img src="assets/images/grad3.avif" alt="">
                    <img src="assets/images/grad4.avif" alt="">
                    <img src="assets/images/grad5.avif" alt="">
                    <img src="assets/images/grad6.avif" alt="">
                </div>
            </div>
                    <div>
                    <div class="showcase-card">
                    <h4 class="title blue">قصص نجاح</h4>
                    <div class="flex expand">
                        <div class="showcase-card-stories-divs">
                            <img src="assets/images/stories/redwomen.avif" alt="" class="showcase-card-stories">
                            <div>
                                <p style="font-size: large;">أ.وردة رياض ضبان</p>
                                <hr>
                                <br>
                                <p>درست فى قسم العلوم الإدارية تخصص إدارة أعمال , قامت بإنشاء مركز تدريبي خاص لتنمية مهارات وقدرات الخريجين</p>
                            </div>
                        </div>
                        <div class="showcase-card-stories-divs">
                            <img src="assets/images/stories/dude.avif" alt="" class="showcase-card-stories">
                            <div>
                                <p style="font-size: large;">أ.مهند بشير محمد شعبان</p>
                                <hr>
                                <br>
                                <p>درس فى قسم الحاسوب تخصص التصميم والمونتاج , قام بإنشاء مركز خاص به مطبعة البشير</p>
                            </div>
                        </div>
                        <div class="showcase-card-stories-divs">
                            <img src="assets/images/stories/handguy.avif" alt="" class="showcase-card-stories">
                            <div>
                                <p style="font-size: large;">أ.حسام عاشور</p>
                                <hr>
                                <br>
                                <p>درس فى قسم العلوم الإدارية, يعمل كمدير لمركز أكسنت للغات</p>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

                    
    </main>
    <footer>
        <div>
            <div class="logo-card">
                <img src="assets/images/logocis.png" alt="" style="width: 100px; height: 100px; object-fit: cover;">
                <p>كلية الدراسات المتوسطة - الأزهر</p>
                <p>College Of Intermediate Studies - Alazhar</p>
                <div class="news-icons">
                    <img src="assets/images/face.svg" alt="">
                    <img src="assets/images/insta.svg" alt="">
                    <img src="assets/images/whatapp.svg" alt="">
                    <img src="assets/images/youtube.svg" alt="">
                    <img src="assets/images/x.svg" alt="">
                </div>
                    <a href="https://play.google.com/store/apps/details?id=com.azharcis.cis&pcampaignid=web_share">                <img src="assets/images/y4EmsywidOA5Qka0C9C3fhH5Ew.png" alt=""
                    style="width: 210px; height: 80px; object-fit: cover;"></a>
            </div>

            <div class="footer-links">

                <div>
                    <p class="blue">
                        روابط سريعة
                    </p>
                    <div>
                        <p><a href="https://moodle.cis.edu.ps/">التعليم الإلكتروني</a></p>

                        <p><a href="https://www.alazhar.edu.ps/arabic/index.asp">جامعة الأزهر</a>
                        </p>
                        <p>صندوق إقراض الطلبة
                        </p>
                        <p>نظام متابعة الخريجين
                        </p>
                    </div>

                </div>
                <div>
                    <p class="blue">
                        الإرشادات
                    </p>
                    <div>
                        <p><a href="html/guidances/academic-system.html">النظام الأكاديمي</a>
                        </p>
                        <p><a href="html/guidances/terms-and-definitions.html">مصطلحات وتعريفات</a>
                        </p>
                        <p><a href="html/guidances/academic-discipline.html">الضبط الأكاديمي</a>
                        </p>
                        <p><a href="html/guidances/grants-and-loans.html">المنح والقورض</a>
                        </p>
                    </div>
                </div>
                <div>
                    <p class="blue">
                        اتصل بنا
                    </p>
                    <div>
                        <p>البريد الإلكتروني: Cis@Alazhar.Edu.Ps
                        </p>
                        <p>غزة , شارع جمال عبد الناصر
                        </p>
                        <p>هاتف : 2641895 9708
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1700.6707854490385!2d34.43668459493744!3d31.514777319476828!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14fd7f40c6369635%3A0x79148138d87401d4!2sIntermediate%20College%20of%20Studies%20-%20Al-Azhar!5e0!3m2!1sen!2s!4v1743591695655!5m2!1sen!2s"
                    width="350" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade ">
                </iframe>
            </div>
        </div>
        <img src="assets/images/nuMfDkeqh8X9tg5eDnYclmy6psY.svg" alt="">
        <div style="display: flex; justify-content: center;">
            <p>جميع الحقوق محفوظة - 2025</p>
        </div>
    </footer>
    <script>
        // Get the burger menu icon and menu elements
        const burgerMenuIcon = document.querySelector(".burger-menu-icon");
        const burgerMenu = document.querySelector(".burger-menu");

        // Add an event listener to the burger menu icon
        burgerMenuIcon.addEventListener("click", () => {
        // Toggle the active class on the burger menu icon
        burgerMenuIcon.classList.toggle("active");

        // Toggle the show class on the menu
        burgerMenu.classList.toggle("show");
        });
        
    </script>
</body>

</html>