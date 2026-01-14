<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['cart'][] = [
        'title' => $_POST['title'] ?? '',
        'price' => (float)($_POST['price'] ?? 0),
        'img'   => $_POST['img'] ?? ''
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['index'])) {
    array_splice($_SESSION['cart'], (int)$_GET['index'], 1);
}
