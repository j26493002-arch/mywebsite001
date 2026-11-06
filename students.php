<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$search = "";

if (isset($_GET["search"])) {
    $search = $_GET["search"];
}

$sort = "id";
$order = "ASC";

if (isset($_GET["sort"])) {
    $sort = $_GET["sort"];
}

if (isset($_GET["order"])) {
    $order = $_GET["order"];
}

if ($sort != "id" && $sort != "name") {
    $sort = "id";
}

if ($order != "ASC" && $order != "DESC") {
    $order = "ASC";
}

$page = 1;

if (isset($_GET["page"])) {
    $page = (int) $_GET["page"];
}

if ($page < 1) {
    $page = 1;
}

$limit = 10;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM students
        WHERE name LIKE ?
        OR email LIKE ?
        OR phone LIKE ?
        ORDER BY $sort $order
        LIMIT ? OFFSET ?";

$stmt = mysqli_prepare($conn, $sql);

$search_value = "%" . $search . "%";

mysqli_stmt_bind_param(
    $stmt,
    "sssii",
    $search_value,
    $search_value,
    $search_value,
    $limit,
    $offset
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);


$sql_count = "SELECT COUNT(*) AS total FROM students
              WHERE name LIKE ?
              OR email LIKE ?
              OR phone LIKE ?";

$stmt_count = mysqli_prepare($conn, $sql_count);

mysqli_stmt_bind_param(
    $stmt_count,
    "sss",
    $search_value,
    $search_value,
    $search_value
);

mysqli_stmt_execute($stmt_count);

$count_result = mysqli_stmt_get_result($stmt_count);
$count_row = mysqli_fetch_assoc($count_result);

$total_students = $count_row["total"];
$total_pages = ceil($total_students / $limit);

mysqli_stmt_close($stmt_count);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>قائمة الطلاب</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container mt-4 mb-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-2">نظام تسجيل الطلاب</h2>

                <p class="text-muted mb-0">
                    مرحباً
                    <?php echo htmlspecialchars($_SESSION["username"]); ?>
                </p>
            </div>

            <a href="logout.php" class="btn btn-primary btn-sm">
                تسجيل الخروج
            </a>

        </div>


        <div class="card shadow-sm">

            <div class="card-body">

                <h3 class="mb-3">قائمة الطلاب</h3>


                <form method="GET" action="" class="row g-2 mb-4">

                    <div class="col-md-5">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="ابحث عن طالب"
                            value="<?php echo htmlspecialchars($search); ?>"
                        >

                    </div>


                    <div class="col-md-2">

                        <select name="sort" class="form-select">

                            <option value="id" <?php if ($sort == "id") echo "selected"; ?>>
                                الرقم
                            </option>

                            <option value="name" <?php if ($sort == "name") echo "selected"; ?>>
                                الاسم
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2">

                        <select name="order" class="form-select">

                            <option value="ASC" <?php if ($order == "ASC") echo "selected"; ?>>
                                تصاعدي
                            </option>
<option value="DESC" <?php if ($order == "DESC") echo "selected"; ?>>
                                تنازلي
                            </option>

                        </select>

                    </div>


                    <div class="col-md-3">

                        <button type="submit" class="btn btn-primary w-100">
                            بحث
                        </button>

                    </div>

                </form>


                <a href="add_student.php" class="btn btn-primary mb-3">
                    إضافة طالب جديد
                </a>


                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>الرقم</th>
                                <th>اسم الطالب</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهاتف</th>
                                <th>العنوان</th>
                                <th>الصورة الشخصية</th>
                                <th>حذف</th>
                                <th>تعديل</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                <tr>

                                    <td>
                                        <?php echo $row["id"]; ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row["name"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row["email"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row["phone"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row["address"]); ?>
                                    </td>


                                    <td>

                                        <?php
                                        if (
                                            !empty($row["profile_picture"]) &&
                                            file_exists($row["profile_picture"])
                                        ) {
                                            echo '<img src="' .
                                                htmlspecialchars($row["profile_picture"]) .
                                                '" width="70" class="img-thumbnail">';
                                        }
                                        ?>

                                    </td>


                                    <td>

                                        <a
                                            href="delete_student.php?id=<?php echo $row["id"]; ?>"
                                            class="btn btn-primary btn-sm"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا الطالب؟')"
                                        >
                                            حذف
                                        </a>

                                    </td>


                                    <td>

                                        <a
                                            href="edit_student.php?id=<?php echo $row["id"]; ?>"
                                            class="btn btn-primary btn-sm"
                                        >
                                            تعديل
                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>
</tbody>

                    </table>

                </div>


                <div class="mt-3">

                    <?php

                    if ($page > 1) {

                        echo '<a class="btn btn-outline-primary btn-sm me-1" href="?page=' .
                            ($page - 1) .
                            '&search=' . urlencode($search) .
                            '&sort=' . $sort .
                            '&order=' . $order .
                            '">السابق</a> ';

                    }


                    for ($i = 1; $i <= $total_pages; $i++) {

                        echo '<a class="btn btn-outline-primary btn-sm me-1" href="?page=' .
                            $i .
                            '&search=' . urlencode($search) .
                            '&sort=' . $sort .
                            '&order=' . $order .
                            '">' . $i . '</a> ';

                    }


                    if ($page < $total_pages) {

                        echo '<a class="btn btn-outline-primary btn-sm" href="?page=' .
                            ($page + 1) .
                            '&search=' . urlencode($search) .
                            '&sort=' . $sort .
                            '&order=' . $order .
                            '">التالي</a>';

                    }

                    ?>

                </div>


            </div>

        </div>

    </div>

</body>
</html>