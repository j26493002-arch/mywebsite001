<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$message = "";

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$student) {
        die("الطالب غير موجود.");
    }
} else {
    die("رقم الطالب غير موجود.");
}

if (isset($_POST["update_student"])) {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $profile_picture = $student["profile_picture"];

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

        $sql = "UPDATE students
                SET name = ?, email = ?, phone = ?, address = ?,
                    profile_picture = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $name,
            $email,
            $phone,
            $address,
            $profile_picture,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: students.php");
            exit;
        } else {
            $message = "حدث خطأ أثناء تعديل بيانات الطالب.";
        }

        mysqli_stmt_close($stmt);
    }

    // تحديث البيانات المعروضة في النموذج بعد محاولة التعديل
    $student["name"] = $name;
    $student["email"] = $email;
    $student["phone"] = $phone;
    $student["address"] = $address;
    $student["profile_picture"] = $profile_picture;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>تعديل بيانات الطالب</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center py-5">

        <div class="card shadow-sm p-4" style="width: 100%; max-width: 600px;">

            <h2 class="text-center mb-4">
                تعديل بيانات الطالب
            </h2>


            <?php if (!empty($message)) { ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>


            <form method="POST" action="" enctype="multipart/form-data">

                <div class="mb-3">

                    <label class="form-label">
                        اسم الطالب
                    </label>
<input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?php echo htmlspecialchars($student["name"]); ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        البريد الإلكتروني
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?php echo htmlspecialchars($student["email"]); ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        رقم الهاتف
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?php echo htmlspecialchars($student["phone"]); ?>"
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
                    ><?php echo htmlspecialchars($student["address"]); ?></textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        الصورة الشخصية الحالية
                    </label>

                    <div class="mt-2">

                        <?php
                        if (
                            !empty($student["profile_picture"]) &&
                            file_exists($student["profile_picture"])
                        ) {
                            echo '<img src="' .
                                htmlspecialchars($student["profile_picture"]) .
                                '" class="img-thumbnail" width="120">';
                        } else {
                            echo '<p class="text-muted">لا توجد صورة شخصية.</p>';
                        }
                        ?>

                    </div>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        اختيار صورة شخصية جديدة
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
                    name="update_student"
                    class="btn btn-primary w-100"
                >
                    حفظ التعديلات
                </button>

            </form>


            <a href="students.php" class="btn btn-outline-secondary w-100 mt-3">
                العودة إلى قائمة الطلاب
            </a>

        </div>

    </div>

</body>
</html>