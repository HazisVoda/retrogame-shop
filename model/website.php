<?php
class Website
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function get(): array
    {
        $stmt = $this->db->query("SELECT * FROM website LIMIT 1");
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: [];
    }

    public function update(array $data): bool
    {
        $sql = "
            UPDATE website SET
              name    = :name,
              details = :details,
              footer  = :footer,
              logo    = :logo
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'    => $data['name'],
            ':details' => $data['details'],
            ':footer'  => $data['footer'],
            ':logo'    => $data['logo'],
        ]);
    }
}
