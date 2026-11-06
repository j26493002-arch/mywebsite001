<?php
include "db.php";

$message = "";

if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "يرجى ملء جميع الحقول.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "يرجى إدخال بريد إلكتروني صحيح.";
    } elseif ($password != $confirm_password) {
        $message = "كلمتا المرور غير متطابقتين.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message = "اسم المستخدم أو البريد الإلكتروني مستخدم مسبقاً.";
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password)
                    VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $username,
                $email,
                $hashed_password
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "تم إنشاء الحساب بنجاح.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إنشاء حساب جديد</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 450px;">

            <h2 class="text-center mb-4">إنشاء حساب جديد</h2>

            <?php if (!empty($message)) { ?>
                <div class="alert alert-info">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="POST" action="">

                <div class="mb-3">
                    <label class="form-label">اسم المستخدم</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" name="register" class="btn btn-primary w-100">
                    إنشاء حساب
                </button>

            </form>

            <p class="text-center mt-3 mb-0">
                لديك حساب بالفعل؟
                <a href="login.php">تسجيل الدخول</a>
            </p>

            <p class="text-center mt-3 mb-0">
                <a href="index.php">العودة إلى الصفحة الرئيسية</a>
            </p>

        </div>
    </div>

</body>
</html>

