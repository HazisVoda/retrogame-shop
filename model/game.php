<?php
class Game {
    public ?int $id = null;
    public string $name;
    public string $category;
    public string $platform;
    public string $details;
    public int $price;
    public int $stock;
    public string $image;
    public string $bgImage;
    public string $dateReleased; //YYYY-MM-DD

    public PDO $db;

    public function __construct(PDO $db, ?int $id = null) {
        $this->db = $db;
        if($id !== null) {
            $this->loadById($id);
        }
    }

    public function loadById(int $id): bool {
        $stmt = $this->db->prepare("SELECT * FROM games WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($game) {
            $this->id = (int)$game['id'];
            $this->name = $game['name'];
            $this->category = $game['category'];
            $this->platform = $game['platform'];
            $this->details = $game['details'];
            $this->price = (int)$game['price'];
            $this->stock = (int)$game['stock'];
            $this->image = $game['image'];
            $this->bgImage = $game['bg_image'];
            $this->dateReleased = $game['date_released'];
            return true;
        }
        return false;
    }

    public static function findByName(PDO $db, string $searchTerm): array {
        $stmt = $db->prepare("SELECT * FROM games WHERE name LIKE :search");
        $stmt->execute([':search' => "%$searchTerm%"]);
        $results = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $game = new Game($db);
            $game->id = (int)$row['id'];
            $game->name = $row['name'];
            $game->category = $row['category'];
            $game->platform = $row['platform'];
            $game->details = $row['details'];
            $game->price = (int)$row['price'];
            $game->stock = (int)$row['stock'];
            $game->image = $row['image'];
            $game->bgImage = $row['bg_image'];
            $game->dateReleased = $row['dateReleased'];
            $results[] = $game;
        }
        return $results;
    }

    public function save(): bool {
        if ($this->id !== null) {
            $stmt = $this->db->prepare("
                UPDATE games SET
                    name = :name,
                    category = :category,
                    platform = :platform,
                    details = :details,
                    price = :price,
                    stock = :stock,
                    image = :image,
                    bgImage = :bgImage,
                    dateReleased = :dateReleased
                WHERE id = :id
            ");
            return $stmt->execute([
                ':name' => $this->name,
                ':category' => $this->category,
                ':platform' => $this->platform,
                ':details' => $this->details,
                ':price' => $this->price,
                ':stock' => $this->stock,
                ':image' => $this->image,
                ':bgImage' => $this->bgImage,
                ':dateReleased' => $this->dateReleased,
                ':id' => $this->id
            ]);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO games
                (name, category, platform, details, price, stock, image, bgImage, dateReleased)
                VALUES
                (:name, :category, :platform, :details, :price, :stock, :image, :bgImage, :dateReleased)
            ");
            $success = $stmt->execute([
                ':name' => $this->name,
                ':category' => $this->category,
                ':platform' => $this->platform,
                ':details' => $this->details,
                ':price' => $this->price,
                ':stock' => $this->stock,
                ':image' => $this->image,
                ':bgImage' => $this->bgImage,
                ':dateReleased' => $this->dateReleased
            ]);
            if ($success) {
                $this->id = (int)$this->db->lastInsertId();
            }
            return $success;
        }
    }

    public function delete(): bool {
        if ($this->id === null) {
            return false;
        }
        $stmt = $this->db->prepare("DELETE FROM games WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public function reduceStock(int $quantity): bool {
        if ($quantity <= 0 || $this->stock < $quantity) {
            return false;
        }
        $this->stock -= $quantity;
        return $this->save();
    }

    public function increaseStock(int $quantity): bool {
        if ($quantity <= 0) {
            return false;
        }
        $this->stock += $quantity;
        return $this->save();
    }
    public function getGamesPaginated(int $page = 1, int $limit = 10, string $search = ''): array {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM games";
        $params = [];

        if ($search !== '') {
            $sql .= " WHERE name LIKE :search OR category LIKE :search";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY dateReleased DESC LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGamesCount(string $search = ''): int {
        $sql = "SELECT COUNT(*) FROM games";
        $params = [];

        if ($search !== '') {
            $sql .= " WHERE name LIKE :search OR category LIKE :search";
            $params[':search'] = "%$search%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getGameById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM games WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $game = $stmt->fetch(PDO::FETCH_ASSOC);
        return $game ?: null;
    }

    public function deleteGameById(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM games WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function saveGame(array $data): bool {
        if (isset($data['id']) && !empty($data['id'])) {
            $sql = "UPDATE games SET name = :name, category = :category, platform = :platform, details = :details, price = :price, stock = :stock, image = :image, bgImage = :bgImage, dateReleased = :dateReleased WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => $data['name'],
                ':category' => $data['category'],
                ':platform' => $data['platform'],
                ':details' => $data['details'],
                ':price' => $data['price'],
                ':stock' => $data['stock'],
                ':image' => $data['image'],
                ':bgImage' => $data['bgImage'],
                ':dateReleased' => $data['dateReleased'],
                ':id' => $data['id']
            ]);
        } else {
            $sql = "INSERT INTO games (name, category, platform, details, price, stock, image, bgImage, dateReleased) VALUES (:name, :category, :platform, :details, :price, :stock, :image, :bgImage, :dateReleased)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => $data['name'],
                ':category' => $data['category'],
                ':platform' => $data['platform'],
                ':details' => $data['details'],
                ':price' => $data['price'],
                ':stock' => $data['stock'],
                ':image' => $data['image'],
                ':bgImage' => $data['bgImage'],
                ':dateReleased' => $data['dateReleased']
            ]);
        }
    }

    public function getAvailableGames(): array {
    $stmt = $this->db->prepare(
        "SELECT * FROM games WHERE stock > 0 ORDER BY dateReleased DESC"
    );
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>