<?php

class LoginController {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function handleLogin(): void {
        session_start();

        $username = $_POST['loginUsername'] ?? '';
        $password = $_POST['loginPassword'] ?? '';

        if (empty($username) || empty($password)) {
            header('Location: ../login.php?error=missing');
            exit();
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            header('Location: ../login.php?error=invalid');
            exit();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['image'] = $user['image'];

        // Create or update token
        $token = bin2hex(random_bytes(32));
        $this->saveToken($user['id'], $token);

        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');

        if($_SESSION['role_id'] == 1) {
            header('Location: ../index.php');
        } else {
            header('Location: ../view/admin/admin_home.php');
        }
        exit();
    }

    private function saveToken(int $userId, string $token): void {
    $stmt = $this->db->prepare("DELETE FROM tokens WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);

    $stmt = $this->db->prepare("INSERT INTO tokens (user_id, token) VALUES (:user_id, :token)");
    $stmt->execute([':user_id' => $userId, ':token' => $token]);
}

}
