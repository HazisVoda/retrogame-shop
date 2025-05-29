<?php
require_once __DIR__ . '/../model/Website.php';

class WebsiteController
{
    private PDO     $db;
    private Website $model;

    public function __construct(PDO $db)
    {
        $this->db    = $db;
        $this->model = new Website($db);
    }

    public function index(): array
    {
        $webInfo = $this->model->get();
        return compact('webInfo');
    }

    /**
     * Handle form POST to update settings.
     */
    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../view/admin/website.php');
            exit();
        }

        // Basic fields
        $data = [
            'name'    => trim($_POST['name']    ?? ''),
            'details' => trim($_POST['details'] ?? ''),
            'footer'  => trim($_POST['footer']  ?? ''),
            'logo'    => trim($_POST['existingLogo'] ?? ''),
        ];

        // Handle logo upload if provided
        if (!empty($_FILES['logo']['tmp_name'])) {
            $uploadDir = __DIR__ . '/../images/uploaded-img/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $file    = $_FILES['logo'];
            $ext     = pathinfo($file['name'], PATHINFO_EXTENSION);
            $base    = pathinfo($file['name'], PATHINFO_FILENAME);
            $safe    = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);
            $name    = $safe . '_' . time() . '.' . $ext;
            $target  = $uploadDir . $name;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // store web‐relative path
                $data['logo'] = 'images/uploaded-img/' . $name;
            }
        }

        // Persist
        $this->model->update($data);

        // Redirect back with a success flag
        header('Location: ../view/admin/website.php?updated=1');
        exit();
    }
    public function update(): void {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $footer = $_POST['footer'] ?? '';

        $logoPath = null;
        if (!empty($_FILES['logo']['tmp_name'])) {
            $uploadDir = __DIR__ . '/../data/uploaded-img/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $safeName = 'logo_' . time() . '.' . $ext;
            $targetPath = $uploadDir . $safeName;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                $logoPath = 'data/uploaded-img/' . $safeName;
            }
        }

        $sql = "UPDATE website SET name = :name, details = :details, footer = :footer" .
            ($logoPath ? ", logo = :logo" : "");
        $params = [
            ':name' => $name,
            ':details' => $description,
            ':footer' => $footer
        ];
        if ($logoPath) $params[':logo'] = $logoPath;

        $this->db->prepare($sql)->execute($params);
    }

}
