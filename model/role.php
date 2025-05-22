<?php
class Role {
    private PDO $db;
    public ?int $id = null;
    public string $roleName;

    public function __construct(PDO $db, ?int $id = null) {
        $this->db = $db;
        if ($id !== null) {
            $this->loadById($id);
        }
    }

    public function loadById(int $id): bool {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($role) {
            $this->id = (int)$role['id'];
            $this->roleName = $role['role_name'];
            return true;
        }
        return false;
    }

    public static function findByRoleName(PDO $db, string $roleName): ?Role {
        $stmt = $db->prepare("SELECT * FROM roles WHERE role_name = :role_name");
        $stmt->execute([':role_name' => $roleName]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($role) {
            $roleObj = new Role($db);
            $roleObj->id = (int)$role['id'];
            $roleObj->roleName = $role['role_name'];
            return $roleObj;
        }
        return null;
    }
}

?>