<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام تسجيل الطلاب</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #6a0dad;">
        <div class="container">
            <a class="navbar-brand" href="index.php">نظام تسجيل الطلاب</a>
        </div>
    </nav>

    <main class="container py-5">
        <div class="text-center bg-white rounded shadow-sm p-4 p-md-5">
            <h1 class="mb-3">مرحباً بكم في نظام تسجيل الطلاب</h1>
            <p class="text-muted mb-4">
                يتيح لكم هذا الموقع تسجيل الدخول وإدارة بيانات الطلاب بسهولة.
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="login.php" class="btn btn-primary">تسجيل الدخول</a>
                <a href="register.php" class="btn btn-outline-primary">إنشاء حساب</a>
                <a href="add_student.php" class="btn btn-success">إضافة طالب</a>
                <a href="students.php" class="btn btn-outline-success">عرض الطلاب</a>
            </div>

            <hr class="my-5">

            <h3 class="mb-4">إعداد الطلاب</h3>

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>اسم الطالب</th>
                            <th>ID</th>
                            <th>class</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>سيرين فخرالدين</td>
                            <td dir="ltr">sirin_237876</td>
                            <td>C3</td>
                        </tr>
                        <tr>
                            <td>جودي عمر</td>
                            <td dir="ltr">joudy_290995</td>
                            <td>C3</td>
                        </tr>
                        <tr>
                            <td>جعفر يونس السلامه</td>
                            <td dir="ltr">jafar_304616</td>
                            <td>C3</td>
                        </tr>
                        <tr>
                            <td>راما محمد خلف</td>
                            <td dir="ltr">rama_242032</td>
                            <td>C3</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="text-center text-muted py-3">
        <p class="mb-0">نظام تسجيل الطلاب</p>
    </footer>

</body>
</html>