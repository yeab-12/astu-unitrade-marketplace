<?php
require_once 'auth.php';
// Define base URL for absolute paths
$base_url = '/unitrade';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniTrade | The Student Marketplace for ASTU</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top glass-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary brand-logo" href="<?= $base_url ?>/index.php">
            <i class="bi bi-cart-dash-fill me-2"></i>UniTrade
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link hover-underline" href="<?= $base_url ?>/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hover-underline" href="<?= $base_url ?>/marketplace.php">Marketplace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hover-underline" href="<?= $base_url ?>/sell.php">Sell</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hover-underline" href="<?= $base_url ?>/contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hover-underline" href="<?= $base_url ?>/about.php">About</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link hover-underline text-accent fw-bold" href="<?= $base_url ?>/admin/dashboard.php">Admin Panel</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link hover-underline" href="<?= $base_url ?>/profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary rounded-pill px-4 ms-2 shadow-sm" href="<?= $base_url ?>/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link hover-underline" href="<?= $base_url ?>/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary rounded-pill px-4 ms-2 shadow-sm" href="<?= $base_url ?>/signup.php">Signup</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>