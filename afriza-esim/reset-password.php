<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/classes/User.php';

// Check if token is valid
$token = isset($_GET['token']) ? $_GET['token'] : '';
$user = new User($pdo);
$userData = $user->validateResetToken($token);

if (!$userData) {
    header('Location: forgot-password.php');
    exit;
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if (empty($password) || empty($confirmPassword)) {
            throw new Exception('Semua field harus diisi');
        }

        if ($password !== $confirmPassword) {
            throw new Exception('Password tidak sama');
        }

        if (strlen($password) < 8) {
            throw new Exception('Password minimal 8 karakter');
        }

        // Update password
        if ($user->updatePassword($userData['id'], $password)) {
            $success = 'Password berhasil direset. Silakan login dengan password baru Anda.';
        } else {
            throw new Exception('Gagal mereset password. Silakan coba lagi.');
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm auth-card">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4 auth-title">Reset Password</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="auth-form">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            <div class="form-text">Minimal 8 karakter</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>