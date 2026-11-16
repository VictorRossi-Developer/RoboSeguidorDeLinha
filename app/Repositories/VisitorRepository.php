<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

/** Consultas SQL da entidade visitors. Nenhuma regra de negócio fica nesta classe. */
final class VisitorRepository
{
    public function __construct(private PDO $db) {}

    /** READ: lista os visitantes mais recentes primeiro. */
    public function all(): array
    {
        return $this->db->query(
            'SELECT id, name, email, interest, created_at FROM visitors ORDER BY created_at DESC, id DESC'
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT id, name, email, interest, created_at FROM visitors WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $visitor = $statement->fetch();
        return $visitor ?: null;
    }

    /** CREATE: statement preparado para evitar injeção de SQL. */
    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO visitors (name, email, interest) VALUES (:name, :email, :interest)'
        );
        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    /** UPDATE: altera somente o registro identificado pelo ID. */
    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $statement = $this->db->prepare(
            'UPDATE visitors SET name = :name, email = :email, interest = :interest WHERE id = :id'
        );
        $statement->execute($data);
        return $statement->rowCount() > 0 || $this->find($id) !== null;
    }

    /** DELETE: a FK da tabela de testes preserva os registros usando ON DELETE SET NULL. */
    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM visitors WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount() > 0;
    }
}
