<?php

class User {
    private PDO $db;

    public ?int $id = null;
    public string $firstName;
    public string $lastName;
    public string $username;
    public string $email;
    private string $passwordHash;
    public int $roleId;
    public string $image;
    public string $createdAt;

    public function __construct(PDO $db, ?int $id = null) {
        $this->db = $db;
        $this->image = 'defaultpfp.png';

        if ($id !== null) {
            $this->loadById($id);
        }
    }
    public function loadById(int $id): bool {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->fillProperties($user);
            return true;
        }
        return false;
    }

    public function loadByUsername(string $username): bool {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->fillProperties($user);
            return true;
        }
        return false;
    }

    public function loadByEmail(string $email): bool {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->fillProperties($user);
            return true;
        }
        return false;
    }

    private function fillProperties(array $data): void {
        $this->id = (int)$data['id'];
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->passwordHash = $data['password'];
        $this->roleId = (int)$data['role_id'];
        $this->image = $data['image'];
        $this->createdAt = $data['createdAt'];
    }

    public function setPassword(string $password): void {
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->passwordHash);
    }

    public function save(): bool {
        if ($this->id !== null) {
            $stmt = $this->db->prepare("
                UPDATE users SET
                    firstName = :firstName,
                    lastName = :lastName,
                    username = :username,
                    email = :email,
                    password = :password,
                    role_id = :role_id,
                    image = :image
                WHERE id = :id
            ");
            return $stmt->execute([
                ':firstName' => $this->firstName,
                ':lastName' => $this->lastName,
                ':username' => $this->username,
                ':email' => $this->email,
                ':password' => $this->passwordHash,
                ':role_id' => $this->roleId,
                ':image' => $this->image,
                ':id' => $this->id
            ]);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO users
                (firstName, lastName, username, email, password, role_id, image)
                VALUES
                (:firstName, :lastName, :username, :email, :password, :role_id, :image)
            ");
            $success = $stmt->execute([
                ':firstName' => $this->firstName,
                ':lastName' => $this->lastName,
                ':username' => $this->username,
                ':email' => $this->email,
                ':password' => $this->passwordHash,
                ':role_id' => $this->roleId,
                ':image' => $this->image
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
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function registeredToday(PDO $db, string $date): int {
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE DATE(createdAt) = :date");
        $stmt->execute([':date' => $date]);
        return $stmt->fetchColumn();
    }

    public static function getUsersRegisteredTodayPaginated(PDO $db, string $date, int $page = 1): array {
        $limit = 5;
        $offset = ($page - 1) * 5;

        $sql = "
            SELECT u.id, u.firstName, u.lastName, u.username, u.email, r.role_name, u.createdAt
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE DATE(u.createdAt) = :date
            LIMIT $limit OFFSET $offset
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':date' => $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersPaginated(int $page = 1, int $limit = 10, string $search = ''): array {
        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT u.id, u.firstName, u.lastName, u.username, u.email, r.role_name, u.createdAt
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
        ";

        $params = [];
        if ($search !== '') {
            $sql .= " WHERE u.username LIKE :search ";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY u.createdAt DESC LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersCount(string $search = ''): int {
        $sql = "SELECT COUNT(*) FROM users";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE username LIKE :search";
            $params[':search'] = "%$search%";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
    public function getDailyNewUsers(string $startDate, string $endDate): array
    {
        $sql = "
            SELECT DATE(createdAt) AS date, COUNT(*) AS count
            FROM users
            WHERE DATE(createdAt) BETWEEN :start AND :end
            GROUP BY DATE(createdAt)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':start' => $startDate,
            ':end'   => $endDate
        ]);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['date']] = (int) $row['count'];
        }
        return $result;
    }

    public function deleteUserById(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function saveUser(array $userData): bool {
        if (isset($userData['id'])) {
            // Update user
            $sql = "UPDATE users SET firstName = :firstName, lastName = :lastName, username = :username, email = :email, role_id = :role_id WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':firstName' => $userData['firstName'],
                ':lastName' => $userData['lastName'],
                ':username' => $userData['username'],
                ':email' => $userData['email'],
                ':role_id' => $userData['role_id'],
                ':id' => $userData['id']
            ]);
        } else {
            $sql = "INSERT INTO users (firstName, lastName, username, email, password, role_id) VALUES (:firstName, :lastName, :username, :email, :password, :role_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':firstName' => $userData['firstName'],
                ':lastName' => $userData['lastName'],
                ':username' => $userData['username'],
                ':email' => $userData['email'],
                ':password' => $userData['password'],
                ':role_id' => $userData['role_id']
            ]);
        }
    }

    public function getUserById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT u.id, u.firstName, u.lastName, u.username, u.email, u.role_id, r.role_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}
