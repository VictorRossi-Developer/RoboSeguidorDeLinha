<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

/** Consultas SQL da entidade test_batteries. */
final class TestBatteryRepository
{
    public function __construct(private PDO $db) {}

    /** READ: faz JOIN para exibir também o nome do visitante relacionado. */
    public function all(): array
    {
        $sql = 'SELECT tb.id, tb.visitor_id, v.name AS visitor_name, tb.avg_speed,
                       tb.lap_time, tb.status, tb.created_at
                FROM test_batteries tb
                LEFT JOIN visitors v ON v.id = tb.visitor_id
                ORDER BY tb.created_at DESC, tb.id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT id, visitor_id, avg_speed, lap_time, status, created_at
             FROM test_batteries WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $record = $statement->fetch();
        return $record ?: null;
    }

    /** CREATE: registra uma execução do robô na pista. */
    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO test_batteries (visitor_id, avg_speed, lap_time, status)
             VALUES (:visitor_id, :avg_speed, :lap_time, :status)'
        );
        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    /** UPDATE: atualiza os dados da execução existente. */
    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $statement = $this->db->prepare(
            'UPDATE test_batteries
             SET visitor_id = :visitor_id, avg_speed = :avg_speed,
                 lap_time = :lap_time, status = :status
             WHERE id = :id'
        );
        $statement->execute($data);
        return $statement->rowCount() > 0 || $this->find($id) !== null;
    }

    /** DELETE: remove o registro de teste pelo ID. */
    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM test_batteries WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount() > 0;
    }
}
