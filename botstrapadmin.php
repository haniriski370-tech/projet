<?php
include 'includes/db-connect.php';

$username = 'admin';
$email    = 'admin@wisetech.dz';
$password = 'admine';
$role     = 'admin';

$stmt = $connect->prepare(
    "INSERT INTO users (id, username, email, role, password) VALUES (1, ?, ?, ?, ?)"
);

if ($stmt === false) {
    die("Prepare failed: " . $connect->error);
}

// "ssss" لأن كل الحقول نصية
$stmt->bind_param("ssss", $username, $email, $role, $password);

if ($stmt->execute()) {
    echo "Admin created. Delete this file now.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$connect->close();
?>
