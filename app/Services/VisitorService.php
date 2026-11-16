<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\VisitorRepository;
use InvalidArgumentException;

/** Regras de negócio e validações dos cadastros de visitantes. */
final class VisitorService
{
    public function __construct(private VisitorRepository $repository) {}

    public function all(): array
    {
        return $this->repository->all();
    }

    public function find(int $id): array
    {
        $visitor = $this->repository->find($id);
        if (!$visitor) {
            throw new InvalidArgumentException('Visitante não encontrado.');
        }
        return $visitor;
    }

    public function create(array $input): int
    {
        return $this->repository->create($this->validate($input));
    }

    public function update(int $id, array $input): void
    {
        $this->find($id);
        if (!$this->repository->update($id, $this->validate($input))) {
            throw new InvalidArgumentException('Não foi possível atualizar o visitante.');
        }
    }

    public function delete(int $id): void
    {
        if (!$this->repository->delete($id)) {
            throw new InvalidArgumentException('Visitante não encontrado para exclusão.');
        }
    }

    /** Conta caracteres UTF-8 mesmo quando a extensão mbstring não está habilitada. */
    private function length(string $value): int
    {
        $count = preg_match_all('/./us', $value);
        return $count === false ? strlen($value) : $count;
    }

    private function validate(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));
        $interest = trim((string) ($input['interest'] ?? ''));

        if ($name === '' || $this->length($name) > 120) {
            throw new InvalidArgumentException('Informe um nome com até 120 caracteres.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $this->length($email) > 150) {
            throw new InvalidArgumentException('Informe um endereço de e-mail válido.');
        }
        if ($interest === '' || $this->length($interest) > 100) {
            throw new InvalidArgumentException('Selecione uma área de interesse válida.');
        }

        return ['name' => $name, 'email' => $email, 'interest' => $interest];
    }
}
