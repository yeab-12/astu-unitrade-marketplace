<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isVerified() {
    return isset($_SESSION['is_verified']) && $_SESSION['is_verified'] == 1;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /unitrade/login.php");
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: /unitrade/index.php");
        exit();
    }
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}
?>
