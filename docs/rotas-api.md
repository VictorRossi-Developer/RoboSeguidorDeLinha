# API PHP — rotas CRUD

Todas as rotas estão centralizadas em `public/api/index.php`. O parâmetro `entity` seleciona a entidade (`visitors` ou `batteries`) e `id` identifica um registro específico.

| Método | URL | Operação |
|---|---|---|
| GET | `api/index.php?entity=visitors` | Lista visitantes |
| GET | `api/index.php?entity=visitors&id=1` | Consulta um visitante |
| POST | `api/index.php?entity=visitors` | Cria visitante |
| PUT | `api/index.php?entity=visitors&id=1` | Atualiza visitante |
| DELETE | `api/index.php?entity=visitors&id=1` | Exclui visitante |
| GET | `api/index.php?entity=batteries` | Lista testes |
| GET | `api/index.php?entity=batteries&id=1` | Consulta um teste |
| POST | `api/index.php?entity=batteries` | Cria teste |
| PUT | `api/index.php?entity=batteries&id=1` | Atualiza teste |
| DELETE | `api/index.php?entity=batteries&id=1` | Exclui teste |

## Exemplos de corpo JSON

Cadastro de visitante:

```json
{
  "name": "Pessoa Visitante",
  "email": "visitante@example.com",
  "interest": "Engenharia e Eletrônica"
}
```

Cadastro de bateria de teste:

```json
{
  "visitor_id": null,
  "avg_speed": 1.25,
  "lap_time": 14.85,
  "status": "Concluído"
}
```

A API retorna JSON com `success`, `message`, `data` ou `id`, conforme a operação. Os comandos SQL utilizam prepared statements PDO.
