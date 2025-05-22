<?php
require_once __DIR__ . '/../model/User.php';

class AdminUsersController {
    private PDO $db;
    private User $userModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    public function listUsers(): array {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $search = $_GET['search'] ?? '';

        $users = $this->userModel->getUsersPaginated($page, 10, $search);
        $totalUsers = $this->userModel->getUsersCount($search);
        $totalPages = max(1, (int)ceil($totalUsers / 10));

        return compact('users', 'totalUsers', 'totalPages', 'page', 'search');
    }

    public function deleteUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                $this->userModel->deleteUserById($id);
            }
        }
        header('Location: ../view/admin/admin_users.php');
        exit();
    }

    public function showUserForm(): array {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $user = null;
        if ($id) {
            $user = $this->userModel->getUserById($id);
            if (!$user) {
                header('Location: admin_users.php');
                exit();
            }
        }
        return compact('user');
    }

    public function saveUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $userData = [
                'id' => $id ? (int)$id : null,
                'firstName' => trim($_POST['firstName']),
                'lastName' => trim($_POST['lastName']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'role_id' => (int)$_POST['role_id'],
            ];

            if (!$userData['id']) {
                if (!empty($_POST['password'])) {
                    $userData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                } else {
                    die('Password required for new users');
                }
            }

            $this->userModel->saveUser($userData);
            header('Location: ../view/admin/admin_users.php');
            exit();
        }
    }
}
