<?php
session_start();
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afriza-eSIM</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <h1 class="m-0"><a href="/" class="text-white text-decoration-none">Afriza-eSIM</a></h1>
                </div>
                <div class="auth-buttons">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard.php" class="btn btn-light me-2"><i class="fas fa-user"></i> Dashboard</a>
                        <a href="/logout.php" class="btn btn-outline-light"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    <?php else: ?>
                        <a href="/login.php" class="btn btn-light me-2"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <a href="/register.php" class="btn btn-outline-light"><i class="fas fa-user-plus"></i> Register</a>
                    <?php endif; ?>
                    <a href="/cart.php" class="btn btn-light ms-2">
                        <i class="fas fa-shopping-cart"></i> <span class="badge bg-danger" id="cart-count">0</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="cartToast" class="toast align-items-center text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i> Produk berhasil ditambahkan ke keranjang
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Floating Navigation -->
    <nav class="navbar fixed-bottom navbar-expand navbar-dark bg-dark d-block d-lg-none">
        <div class="container justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="/"><i class="fas fa-home"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/products.php"><i class="fas fa-list"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/how-to-order.php"><i class="fas fa-question-circle"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact.php"><i class="fas fa-envelope"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="container mt-4 mb-5 pb-3">