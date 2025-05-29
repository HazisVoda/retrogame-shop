<?php
require_once __DIR__ . '/../model/game.php';

class HomeController
{
    private PDO  $db;
    private Game $gameModel;

    public function __construct(PDO $db)
    {
        $this->db        = $db;
        $this->gameModel = new Game($db);
    }

    public function index(): array
    {
        // 1) Most recent 5 games in stock
        $sqlRecent = "
            SELECT *
            FROM games
            WHERE stock > 0
            ORDER BY addedAt DESC
            LIMIT 4
        ";
        $stmt       = $this->db->prepare($sqlRecent);
        $stmt->execute();
        $recentGames = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2) Top 5 selling games (sum of order_items.quantity)
        $sqlTop = "
            SELECT
              g.*,
              COALESCE(SUM(oi.quantity), 0) AS soldCount
            FROM games g
            LEFT JOIN order_items oi
              ON g.id = oi.game_id
            WHERE g.stock > 0
            GROUP BY g.id
            ORDER BY soldCount DESC
            LIMIT 6
        ";
        $stmt          = $this->db->prepare($sqlTop);
        $stmt->execute();
        $topSellingGames = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return compact('recentGames', 'topSellingGames');
    }
}
