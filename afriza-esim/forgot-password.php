<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/classes/User.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User($pdo);
        $email = trim($_POST['email']);
        
        if (empty($email)) {
            throw new Exception('Email harus diisi');
        }

        if (!$user->emailExists($email)) {
            throw new Exception('Email tidak terdaftar');
        }

        // Generate reset token
        $token = $user->createResetToken($email);
        
        // Send reset email (simulated in this example)
        $resetLink = "http://{$_SERVER['HTTP_HOST']}/reset-password.php?token=$token";
        $message = "Jika email terdaftar, kami telah mengirimkan link reset password. Link: $resetLink";

    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm auth-card">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4 auth-title">Lupa Password</h2>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?= strpos($message, 'mengirimkan') !== false ? 'success' : 'danger' ?>">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="auth-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Instruksi Reset
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center auth-links">
                        <p class="mb-0">Ingat password? <a href="login.php" class="text-decoration-none">Login disini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>