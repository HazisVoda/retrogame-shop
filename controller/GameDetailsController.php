<?php
require_once __DIR__ . '/../model/game.php';

class GameDetailsController
{
    private PDO  $db;
    private Game $gameModel;

    public function __construct(PDO $db)
    {
        $this->db        = $db;
        $this->gameModel = new Game($db);
    }

    /**
     * Fetch one game and build a small gallery array
     * @return array{game: array, gallery: string[]}
     */
    public function show(): array
    {
        // 1) Validate & grab ID
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: shop.php');
            exit;
        }

        // 2) Load the game
        $game = $this->gameModel->getGameById($id);
        if (!$game || $game['stock'] < 1) {
            header('Location: shop.php');
            exit;
        }

        // 3) Build a small gallery of images: cover + optional bgImage
        $gallery = [];
        if (!empty($game['image'])) {
            $gallery[] = $game['image'];
        }
        if (!empty($game['bgImage'])) {
            $gallery[] = $game['bgImage'];
        }

        return [
            'game'    => $game,
            'gallery' => $gallery
        ];
    }

    /** unchanged **/
    public function index(): array
    {
        $games = $this->gameModel->getAvailableGames();
        return ['games' => $games];
    }
}
