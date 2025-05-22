<?php
require_once __DIR__ . '/../model/user.php';

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

    /** Render form data */
    public function showForm(): array
    {
        // Pull from model into an array for extract()
        return ['user' => [
            'firstName' => $this->userModel->firstName,
            'lastName'  => $this->userModel->lastName,
            'username'  => $this->userModel->username,
            'email'     => $this->userModel->email,
            'image'     => $this->userModel->image,
        ]];
    }

    /** Handle POST submission */
    public function save(): void
    {
        // 1) Update basic fields
        $this->userModel->firstName = trim($_POST['firstName']);
        $this->userModel->lastName  = trim($_POST['lastName']);
        $this->userModel->username  = trim($_POST['username']);
        $this->userModel->email     = trim($_POST['email']);

        // 2) Optional password update
        if (!empty($_POST['password'])) {
            $this->userModel->setPassword($_POST['password']);
        }

        // 3) Profile‐image upload
        if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
            $uploadDir = __DIR__ . '/../images/uploaded-img/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $file     = $_FILES['image'];
            $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
            $base     = pathinfo($file['name'], PATHINFO_FILENAME);
            $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);
            $name     = $safeBase . '_' . time() . ".$ext";
            $target   = $uploadDir . $name;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // store web‐relative path
                $this->userModel->image = 'images/uploaded-img/' . $name;
            }
        }
        // else: keep existing image untouched

        // 4) Persist changes
        $this->userModel->save();

        header('Location: ../view/profile.php?updated=1');
        exit();
    }
}
