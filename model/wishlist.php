<?php
class Wishlist {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /** Add a game to the user’s wishlist */
    public function add(int $userId, int $gameId): bool {
        $sql = "
            INSERT IGNORE INTO wishlist (user_id, game_id)
            VALUES (:user_id, :game_id)
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':game_id' => $gameId
        ]);
    }

    /** Remove a game from the user’s wishlist */
    public function remove(int $userId, int $gameId): bool {
        $sql = "
            DELETE FROM wishlist
            WHERE user_id = :user_id
              AND game_id = :game_id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':game_id' => $gameId
        ]);
    }

    /** Return an array of wishlist entries (game_id, added_at) for a user */
    public function getItems(int $userId): array {
        $sql = "
            SELECT game_id, added_at
            FROM wishlist
            WHERE user_id = :user_id
            ORDER BY added_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Check if a given game is already in the user’s wishlist */
    public function exists(int $userId, int $gameId): bool {
        $sql = "
            SELECT 1 FROM wishlist
            WHERE user_id = :user_id
              AND game_id = :game_id
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':game_id' => $gameId
        ]);
        return (bool)$stmt->fetchColumn();
    }
    public function countItems(int $userId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM wishlist
            WHERE user_id = :uid
        ");
        $stmt->execute([':uid' => $userId]);
        return (int)$stmt->fetchColumn();
    }
}
