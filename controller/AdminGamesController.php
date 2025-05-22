<?php
require_once __DIR__ . '/../model/game.php';

class AdminGamesController {
    private PDO $db;
    private Game $gameModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->gameModel = new Game($db);
    }

    public function listGames(): array {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $search = $_GET['search'] ?? '';

        $games = $this->gameModel->getGamesPaginated($page, 10, $search);
        $totalGames = $this->gameModel->getGamesCount($search);
        $totalPages = max(1, (int)ceil($totalGames / 10));
        return compact('games', 'page', 'search', 'totalPages');
    }

    public function deleteGame(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                $this->gameModel->deleteGameById($id);
            }
        }
        header('Location: ../view/admin/admin_games.php');
        exit();
    }

    public function showGameForm(): array {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $game = null;
        if ($id) {
            $game = $this->gameModel->getGameById($id);
            if (!$game) {
                header('Location: ../view/admin/admin_games.php');
                exit();
            }
        }
        return compact('game');
    }

    public function saveGame(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $coverImage = $_POST['image'] ?? '';
            $bgImage = $_POST['bgImage'] ?? '';

            $data = [
                'id' => $_POST['id'] ?? null,
                'name' => $_POST['name'],
                'category' => $_POST['category'],
                'platform' => $_POST['platform'],
                'details' => $_POST['details'],
                'price' => (float)$_POST['price'],
                'stock' => (int)$_POST['stock'],
                'dateReleased' => $_POST['dateReleased']
            ];

            $uploadDir = __DIR__ . '/../data/uploaded-img/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $uploadFile = function(array $file) use ($uploadDir) {
                if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
                    return null;
                }
                $ext        = pathinfo($file['name'], PATHINFO_EXTENSION);
                $base       = pathinfo($file['name'], PATHINFO_FILENAME);
                $safeBase   = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);
                $filename   = sprintf('%s_%s.%s', $safeBase, time(), $ext);
                $targetPath = $uploadDir . $filename;

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    return 'data/uploaded-img/' . $filename;
                }

                throw new \Exception("Failed to move uploaded file {$file['name']}");
            };

            if (!empty($_FILES['image']['tmp_name'])) {
                $path = $uploadFile($_FILES['image']);
                if ($path) {
                    $data['image'] = $path;
                }
            } else {
                $data['image'] = $_POST['existingImage'] ?? '';
            }

            // 4) Handle background image
            if (!empty($_FILES['bgImage']['tmp_name'])) {
                $path = $uploadFile($_FILES['bgImage']);
                if ($path) {
                    $data['bgImage'] = $path;
                }
            } else {
                $data['bgImage'] = $_POST['existingBgImage'] ?? '';
            }

            $this->gameModel->saveGame($data);
            header('Location: ../view/admin/admin_games.php');
            exit();
        }
    }
}
