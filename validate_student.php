<?php
$name = trim($_POST["name"]);
$email = trim($_POST["email"]);

$errors = array();

if ($name == "") {
    $errors[] = "name";
}

if ($email == "") {
    $errors[] = "email_empty";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "email_invalid";
}

if (empty($errors)) {
    echo "valid";
} else {
    echo implode(",", $errors);
}
?>
