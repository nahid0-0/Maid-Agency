<?php

namespace Models;

use Core\Database;

/**
 * Base model class with common database operations
 */
abstract class BaseModel
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find record by ID
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Alias for find method
     */
    public function getById(int $id): ?array
    {
        return $this->find($id);
    }

    /**
     * Find all records
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }

    /**
     * Create new record
     */
    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        
        if (empty($data)) {
            throw new \Exception("No valid data provided for creation");
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        return $this->db->insert($sql, $data);
    }

    /**
     * Update record by ID
     */
    public function update(int $id, array $data): bool
    {
        $data = $this->filterFillable($data);
        
        if (empty($data)) {
            return false;
        }

        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $data['id'] = $id;
        
        return $this->db->execute($sql, $data) > 0;
    }

    /**
     * Delete record by ID
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->execute($sql, [$id]) > 0;
    }

    /**
     * Filter data to only include fillable fields
     */
    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Get table name
     */
    public function getTable(): string
    {
        return $this->table;
    }
}