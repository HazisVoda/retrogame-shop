<?php
require_once __DIR__ . '/../model/Wishlist.php';
require_once __DIR__ . '/../model/Game.php';

class WishlistController {
    private PDO $db;
    private Wishlist $wishlistModel;
    private Game     $gameModel;

    public function __construct(PDO $db) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../login.php');
            exit();
        }
        $this->db             = $db;
        $this->wishlistModel  = new Wishlist($db);
        $this->gameModel      = new Game($db);
    }

    /** Show all games in the current user’s wishlist */
    public function index(): array {
        $userId = (int)$_SESSION['user_id'];
        $entries = $this->wishlistModel->getItems($userId);
        $games = [];
        foreach ($entries as $row) {
            $game = $this->gameModel->getGameById((int)$row['game_id']);
            if ($game && $game['stock'] > 0) {
                $games[] = $game;
            }
        }
        return ['games' => $games];
    }

    /** Handle adding a game */
    public function add(int $gameId): void {
        $userId = (int)$_SESSION['user_id'];
        $this->wishlistModel->add($userId, $gameId);
        header('Location: ../view/wishlist.php');
        exit();
    }

    /** Handle removing a game */
    public function remove(int $gameId): void {
        $userId = (int)$_SESSION['user_id'];
        $this->wishlistModel->remove($userId, $gameId);
        header('Location: ../view/wishlist.php');
        exit();
    }
}
