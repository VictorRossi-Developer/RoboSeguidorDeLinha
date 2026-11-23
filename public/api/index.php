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
    if ($entity === 'opinions') {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'GET') {
            $db = Database::connection();
            $statement = $db->query('SELECT level, COUNT(*) AS total FROM opinions GROUP BY level');
            $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $counts[(int) $row['level']] = (int) $row['total'];
            }
            $total = array_sum($counts);
            $commentsStatement = $db->query("SELECT name, level, comment, created_at FROM opinions WHERE comment IS NOT NULL AND TRIM(comment) <> '' ORDER BY created_at DESC, id DESC LIMIT 50");
            $comments = $commentsStatement->fetchAll(PDO::FETCH_ASSOC);
            respond(['success' => true, 'data' => ['counts' => $counts, 'total' => $total, 'comments' => $comments]]);
        }
        if ($method === 'POST') {
            $input = requestData();
            $name = trim((string) ($input['name'] ?? ''));
            $level = filter_var($input['level'] ?? null, FILTER_VALIDATE_INT);
            $comment = trim((string) ($input['comment'] ?? ''));
            if ($name === '' || mb_strlen($name, 'UTF-8') > 120 || preg_match('/[<>]/u', $name)) {
                respond(['success' => false, 'message' => 'Informe um nome válido com até 120 caracteres.'], 422);
            }
            if ($level === false || $level === null || $level < 1 || $level > 5) {
                respond(['success' => false, 'message' => 'Selecione uma avaliação entre os níveis 1 e 5.'], 422);
            }
            if (preg_match('/[<>]/', $comment) || strlen($comment) > 500) {
                respond(['success' => false, 'message' => 'O comentário deve ter até 500 caracteres e não pode conter marcação HTML.'], 422);
            }
            $db = Database::connection();
            $insert = $db->prepare('INSERT INTO opinions (name, level, comment) VALUES (:name, :level, :comment)');
            $insert->execute(['name' => $name, 'level' => $level, 'comment' => $comment !== '' ? $comment : null]);
            respond(['success' => true, 'message' => 'Sua opinião foi registrada. Obrigado pela participação, ' . $name . '!'], 201);
        }
        header('Allow: GET, POST');
        respond(['success' => false, 'message' => 'Método não permitido.'], 405);
    }
    $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

    $db = Database::connection();
    $visitorRepository = new VisitorRepository($db);
    $batteryRepository = new TestBatteryRepository($db);
    $batteryService = new TestBatteryService($batteryRepository, $visitorRepository);

    $service = match ($entity) {
        'batteries' => $batteryService,
        default => null,
    };

    if ($service === null) {
        respond(['success' => false, 'message' => 'Entidade inválida. Use batteries ou opinions.'], 404);
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
    respond(['success' => false, 'message' => 'Ocorreu um erro interno ao processar a solicitação. Tente novamente.'], 500);
}
