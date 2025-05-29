<?php
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/order.php';
require_once __DIR__ . '/../model/wishlist.php';

class ProfileController
{
    private PDO  $db;
    private User $userModel;

    public function __construct(PDO $db)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../view/login.php');
            exit();
        }
        $this->db        = $db;
        $this->userModel = new User($db, (int)$_SESSION['user_id']);
    }
    public function showForm(): array
    {
        // Basic user data
        $user = [
            'firstName' => $this->userModel->firstName,
            'lastName'  => $this->userModel->lastName,
            'username'  => $this->userModel->username,
            'email'     => $this->userModel->email,
            'image'     => $this->userModel->image,
            'created_at'=> $this->userModel->createdAt ?? null,
        ];

        // Tabs
        $activeTab   = $_GET['tab'] ?? 'profile';
        $editMode    = ($activeTab === 'profile' && isset($_GET['edit']));
        $showSuccess = isset($_GET['updated']);

        // Orders
        $orders = [];
        if ($activeTab === 'orders') {
            $orderModel = new Order($this->db);
            $orders = $orderModel->getOrdersByUser((int)$_SESSION['user_id']);
        }

        // Wishlist count
        $wishlistCount = 0;
        if ($activeTab === 'wishlist') {
            $wishlistModel = new Wishlist($this->db);
            $wishlistCount = $wishlistModel->countItems((int)$_SESSION['user_id']);
        }

        return compact('user','activeTab','editMode','showSuccess','orders','wishlistCount');
    }

    /**
     * Process profile update
     */
    public function save(): void
    {
        // Update fields
        $this->userModel->firstName = trim($_POST['firstName']);
        $this->userModel->lastName  = trim($_POST['lastName']);
        $this->userModel->username  = trim($_POST['username']);
        $this->userModel->email     = trim($_POST['email']);

        // Password
        if (!empty($_POST['password'])) {
            $this->userModel->setPassword($_POST['password']);
        }

        // Profile image upload
        if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
            $uploadDir = __DIR__ . '/../images/uploaded-img/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $file = $_FILES['image'];
            $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
            $base = pathinfo($file['name'], PATHINFO_FILENAME);
            $safe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);
            $name = $safe . '_' . time() . "." . $ext;
            $target = $uploadDir . $name;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $this->userModel->image = 'images/uploaded-img/' . $name;
            }
        }

        // Save
        $this->userModel->save();
        header('Location: ../view/profile.php?updated=1&tab=profile');
        exit();
    }
}
