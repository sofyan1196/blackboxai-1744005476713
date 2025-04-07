<?php
require_once __DIR__ . '/includes/header.php';

// Check if redirected from successful registration
if (!isset($_SESSION['register_success'])) {
    header('Location: register.php');
    exit;
}

unset($_SESSION['register_success']);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card register-success-card">
                <div class="card-body text-center p-5">
                    <div class="success-icon">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="mb-3">Pendaftaran Berhasil!</h2>
                    <p class="mb-4">Akun Anda telah berhasil dibuat. Silakan login untuk mulai menggunakan layanan kami.</p>
                    <div class="d-grid gap-2">
                        <a href="login.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i> Login Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>