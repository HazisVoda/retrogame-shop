<?php
require_once __DIR__ . '/../model/game.php';

class GameController
{
    private PDO  $db;
    private Game $gameModel;

    public function __construct(PDO $db)
    {
        $this->db        = $db;
        $this->gameModel = new Game($db);
    }

    /** List all in-stock games */
    public function index(): array
    {
        $games = $this->gameModel->getAvailableGames();
        return ['games' => $games];
    }

    /** Show details for one game */
    public function show(): array
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: /view/games.php');
            exit;
        }
        $game = $this->gameModel->getGameById($id);
        if (!$game || $game['stock'] < 1) {
            header('Location: /view/games.php');
            exit;
        }
        return ['game' => $game];
    }
}
