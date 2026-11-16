<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\TestBatteryRepository;
use App\Repositories\VisitorRepository;
use InvalidArgumentException;

/** Regras e validações dos registros de testes realizados pelo robô. */
final class TestBatteryService
{
    public function __construct(
        private TestBatteryRepository $repository,
        private VisitorRepository $visitorRepository
    ) {}

    public function all(): array
    {
        return $this->repository->all();
    }

    public function find(int $id): array
    {
        $record = $this->repository->find($id);
        if (!$record) {
            throw new InvalidArgumentException('Registro de teste não encontrado.');
        }
        return $record;
    }

    public function create(array $input): int
    {
        return $this->repository->create($this->validate($input));
    }

    public function update(int $id, array $input): void
    {
        $this->find($id);
        if (!$this->repository->update($id, $this->validate($input))) {
            throw new InvalidArgumentException('Não foi possível atualizar o teste.');
        }
    }

    public function delete(int $id): void
    {
        if (!$this->repository->delete($id)) {
            throw new InvalidArgumentException('Registro de teste não encontrado para exclusão.');
        }
    }

    private function validate(array $input): array
    {
        $visitorId = isset($input['visitor_id']) && $input['visitor_id'] !== ''
            ? (int) $input['visitor_id'] : null;
        $speed = filter_var($input['avg_speed'] ?? null, FILTER_VALIDATE_FLOAT);
        $lapTime = filter_var($input['lap_time'] ?? null, FILTER_VALIDATE_FLOAT);
        $status = trim((string) ($input['status'] ?? 'Concluído'));
        $allowedStatuses = ['Concluído', 'Recorde da Feira', 'Interrompido'];

        if ($speed === false || $speed < 0 || $speed > 999.99) {
            throw new InvalidArgumentException('Informe uma velocidade entre 0 e 999,99 m/s.');
        }
        if ($lapTime === false || $lapTime <= 0 || $lapTime > 9999.99) {
            throw new InvalidArgumentException('Informe um tempo de volta maior que zero.');
        }
        if (!in_array($status, $allowedStatuses, true)) {
            throw new InvalidArgumentException('Selecione um status de teste válido.');
        }
        if ($visitorId !== null && !$this->visitorRepository->find($visitorId)) {
            throw new InvalidArgumentException('O visitante selecionado não existe.');
        }

        return [
            'visitor_id' => $visitorId,
            'avg_speed' => number_format((float) $speed, 2, '.', ''),
            'lap_time' => number_format((float) $lapTime, 2, '.', ''),
            'status' => $status,
        ];
    }
}
