<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/classes/User.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User($pdo);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception('Semua field harus diisi');
        }

        if ($password !== $confirmPassword) {
            throw new Exception('Password tidak sama');
        }

        if (strlen($password) < 8) {
            throw new Exception('Password minimal 8 karakter');
        }

        if ($user->emailExists($email)) {
            throw new Exception('Email sudah terdaftar');
        }

        // Register user
        if ($user->register($username, $email, $password)) {
            $_SESSION['register_success'] = true;
            header('Location: register-success.php');
            exit;
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
                    <h2 class="card-title text-center mb-4 auth-title">Daftar Akun</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="auth-form">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            <div class="form-text">Minimal 8 karakter</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i> Daftar
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center auth-links">
                        <p class="mb-0">Sudah punya akun? <a href="login.php" class="text-decoration-none">Login disini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>