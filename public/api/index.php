<?php
declare(strict_types=1);

use App\Config\Database;
use App\Repositories\TestBatteryRepository;
use App\Repositories\VisitorRepository;
use App\Services\TestBatteryService;
use App\Services\VisitorService;

require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function respond(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function requestData(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        respond(['success' => false, 'message' => 'O corpo da requisição precisa conter um JSON válido.'], 400);
    }
    return $data;
}

try {
    $entity = (string) ($_GET['entity'] ?? '');
    $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

    $db = Database::connection();
    $visitorRepository = new VisitorRepository($db);
    $batteryRepository = new TestBatteryRepository($db);
    $visitorService = new VisitorService($visitorRepository);
    $batteryService = new TestBatteryService($batteryRepository, $visitorRepository);

    $service = match ($entity) {
        'visitors' => $visitorService,
        'batteries' => $batteryService,
        default => null,
    };

    if ($service === null) {
        respond(['success' => false, 'message' => 'Entidade inválida. Use visitors ou batteries.'], 404);
    }

    switch ($method) {
        case 'GET':
            if ($id !== null && $id !== false) {
                respond(['success' => true, 'data' => $service->find((int) $id)]);
            }
            respond(['success' => true, 'data' => $service->all()]);

        case 'POST':
            $newId = $service->create(requestData());
            respond(['success' => true, 'message' => 'Registro criado com sucesso.', 'id' => $newId], 201);

        case 'PUT':
            if (!$id || $id < 1) {
                respond(['success' => false, 'message' => 'Informe um ID válido para atualizar.'], 400);
            }
            $service->update((int) $id, requestData());
            respond(['success' => true, 'message' => 'Registro atualizado com sucesso.']);

        case 'DELETE':
            if (!$id || $id < 1) {
                respond(['success' => false, 'message' => 'Informe um ID válido para excluir.'], 400);
            }
            $service->delete((int) $id);
            respond(['success' => true, 'message' => 'Registro excluído com sucesso.']);

        default:
            header('Allow: GET, POST, PUT, DELETE');
            respond(['success' => false, 'message' => 'Método não permitido.'], 405);
    }
} catch (InvalidArgumentException $exception) {
    respond(['success' => false, 'message' => $exception->getMessage()], 422);
} catch (PDOException $exception) {
    // Violação de UNIQUE (e-mail duplicado) recebe retorno amigável.
    if ($exception->getCode() === '23000') {
        respond(['success' => false, 'message' => 'Este e-mail já está cadastrado ou existe uma relação inválida.'], 409);
    }
    error_log('Erro SQL na API: ' . $exception->getMessage());
    respond(['success' => false, 'message' => 'O banco de dados não conseguiu concluir a operação.'], 500);
} catch (Throwable $exception) {
    error_log('Erro inesperado na API: ' . $exception->getMessage());
    respond(['success' => false, 'message' => $exception->getMessage()], 500);
}
