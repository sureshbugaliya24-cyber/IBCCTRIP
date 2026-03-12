<?php
// backend/models/BaseModel.php
// ============================================================
// Abstract Base Model — OOP Data Access Layer
// ============================================================

abstract class BaseModel
{
    protected PDO $pdo;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(array $conditions = [], array $options = []): array
    {
        $sql = "SELECT * FROM `{$this->table}`";

        if (!empty($conditions)) {
            $clauses = [];
            foreach (array_keys($conditions) as $col) {
                $clauses[] = "`$col` = :$col";
            }
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }

        if (!empty($options['order'])) {
            $sql .= ' ORDER BY ' . $options['order'];
        }

        if (!empty($options['limit'])) {
            $sql .= ' LIMIT ' . (int) $options['limit'];
            if (!empty($options['offset'])) {
                $sql .= ' OFFSET ' . (int) $options['offset'];
            }
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findBy(string $column, $value): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `{$this->table}` WHERE `$column` = ?");
        $stmt->execute([$value]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function insert(array $data): int
    {
        $cols = implode(', ', array_map(fn($c) => "`$c`", array_keys($data)));
        $vals = implode(', ', array_map(fn($c) => ":$c", array_keys($data)));
        $sql = "INSERT INTO `{$this->table}` ($cols) VALUES ($vals)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($c) => "`$c` = :$c", array_keys($data)));
        $sql = "UPDATE `{$this->table}` SET $sets WHERE `{$this->primaryKey}` = :__id";
        $data['__id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?");
        return $stmt->execute([$id]);
    }

    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if (!empty($conditions)) {
            $clauses = array_map(fn($c) => "`$c` = :$c", array_keys($conditions));
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);
        return (int) $stmt->fetchColumn();
    }

    protected function sanitize(string $str): string
    {
        return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
    }
}
