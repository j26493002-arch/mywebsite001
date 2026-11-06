<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$message = "";

if (isset($_POST["name"]) && isset($_POST["email"])) {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $profile_picture = "";

    if (empty($name) || empty($email)) {
        $message = "يرجى ملء الحقول المطلوبة.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "يرجى إدخال بريد إلكتروني صحيح.";
    } else {

        if (
            isset($_FILES["profile_picture"]) &&
            $_FILES["profile_picture"]["error"] == 0
        ) {
            $file_type = $_FILES["profile_picture"]["type"];

            if (strpos($file_type, "image/") === 0) {

                $target_dir = "uploads/";

                if (!is_dir($target_dir)) {
                    mkdir($target_dir);
                }

                $file_name = basename($_FILES["profile_picture"]["name"]);
                $target_file = $target_dir . time() . "_" . $file_name;

                if (move_uploaded_file(
                    $_FILES["profile_picture"]["tmp_name"],
                    $target_file
                )) {
                    $profile_picture = $target_file;
                }
            }
        }

        $sql = "INSERT INTO students
                (name, email, phone, address, profile_picture)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $name,
            $email,
            $phone,
            $address,
            $profile_picture
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "تمت إضافة الطالب بنجاح.";
        } else {
            $message = "حدث خطأ أثناء إضافة الطالب.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إضافة طالب</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container mt-5 mb-5">

        <div class="row justify-content-center">

            <div class="col-12 col-md-8 col-lg-6">

                <div class="card shadow-sm">

                    <div class="card-body p-4">

                        <h2 class="text-center mb-4">
                            إضافة طالب جديد
                        </h2>

                        <?php if ($message != "") { ?>

                            <?php if ($message == "تمت إضافة الطالب بنجاح.") { ?>

                                <div class="alert alert-success">
                                    <?php echo htmlspecialchars($message); ?>
                                </div>

                            <?php } else { ?>

                                <div class="alert alert-danger">
                                    <?php echo htmlspecialchars($message); ?>
                                </div>

                            <?php } ?>

                        <?php } ?>

                        <form
                            method="POST"
                            action=""
                            id="studentForm"
                            enctype="multipart/form-data"
                            novalidate
                        >

                            <div class="mb-3">

                                <label class="form-label">
                                    اسم الطالب
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    required
                                >
<span id="nameError" class="text-danger"></span>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    البريد الإلكتروني
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required
                                >

                                <span id="emailError" class="text-danger"></span>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    رقم الهاتف
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                >

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    العنوان
                                </label>

                                <textarea
                                    name="address"
                                    class="form-control"
                                    rows="3"
                                ></textarea>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    الصورة الشخصية (اختياري)
                                </label>

                                <input
                                    type="file"
                                    name="profile_picture"
                                    class="form-control"
                                    accept="image/*"
                                >

                            </div>

                            <button
                                type="submit"
                                name="add_student"
                                class="btn btn-primary w-100"
                            >
                                إضافة الطالب
                            </button>

                        </form>

                        <div class="text-center mt-3">

                            <a href="students.php" class="btn btn-outline-secondary">
                                العودة إلى قائمة الطلاب
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="script.js"></script>

</body>
</html>