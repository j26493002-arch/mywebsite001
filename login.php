<?php
include "db.php";

session_start();

$message = "";

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $id, $db_username, $db_password);

    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $db_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $db_username;

            header("Location: students.php");
            exit;
        } else {
            $message = "اسم المستخدم أو البريد الإلكتروني أو كلمة المرور غير صحيحة.";
        }
    } else {
        $message = "اسم المستخدم أو البريد الإلكتروني أو كلمة المرور غير صحيحة.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">

                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        <h2 class="text-center mb-4">تسجيل الدخول</h2>

                        <?php if ($message != "") { ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php } ?>

                        <form method="POST" action="">

                            <div class="mb-3">
                                <label class="form-label">اسم المستخدم أو البريد الإلكتروني</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">كلمة المرور</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" name="login" class="btn btn-primary w-100">
                                تسجيل الدخول
                            </button>

                        </form>

                        <p class="text-center mt-3 mb-0">
                            ليس لديك حساب؟
                            <a href="register.php">إنشاء حساب جديد</a>
                        </p>

                        <p class="text-center mt-3 mb-0">
                            <a href="index.php">العودة إلى الصفحة الرئيسية</a>
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>