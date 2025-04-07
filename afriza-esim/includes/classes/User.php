<?php
class User {
    private $pdo;
    private $table = 'users';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Register new user
    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $hashedPassword]);
    }

    // Login user
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] == 0) {
                throw new Exception("Account not activated. Please check your email.");
            }
            return $user;
        }
        return false;
    }

    // Check if email exists
    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // Get user by ID
    public function getUser($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Activate account
    public function activateAccount($email) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET status = 1 WHERE email = ?");
        return $stmt->execute([$email]);
    }

    // Update password
    public function updatePassword($email, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET password = ? WHERE email = ?");
        return $stmt->execute([$hashedPassword, $email]);
    }
}
?>