<?php
$conn = mysqli_connect("localhost", "root", "", "student_registration_system");

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}
?>