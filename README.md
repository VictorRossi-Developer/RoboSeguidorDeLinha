# LINA-01 — Robô Seguidor de Linha IoT | Feira Tecnológica

Aplicação web em PHP para apresentar o protótipo LINA-01 e demonstrar os requisitos da atividade: arquitetura baseada em serviços, persistência em banco relacional e operações CRUD completas para duas entidades principais.

> A simulação desenhada no navegador é uma demonstração visual. Ela não envia comandos ao robô e não representa telemetria real do ESP32.

## Objetivo

Apresentar o robô seguidor de linha desenvolvido para a Feira Tecnológica e oferecer uma experiência interativa ao público. A aplicação registra visitantes e baterias de testes realizadas na pista, mantendo os dados em MySQL.

## Tecnologias

- PHP 8.1 ou superior
- MySQL 8.0 ou superior
- PDO e prepared statements
- HTML5, Tailwind CSS via CDN, CSS3 e JavaScript
- API HTTP com respostas JSON
- XAMPP para ambiente local de desenvolvimento

## Funcionalidades

- Página de apresentação do LINA-01 e explicação da arquitetura.
- Simulador de pista no Canvas: circuitos predefinidos, desenho livre, pausa e retomada.
- Animações de entrada com `IntersectionObserver` e menu adaptado para dispositivos móveis.
- Abas que explicam as camadas de serviços, repositórios e conexão PDO.
- CRUD persistente de visitantes: criar, listar, editar e excluir.
- CRUD persistente de baterias de teste: criar, listar, editar e excluir.
- Pesquisa nas listagens e associação opcional de um teste a um visitante.
- Modelo lógico relacional e script SQL comentado.

## Estrutura de pastas

```text
linebot-lina/
├── app/
│   ├── Config/
│   │   └── Database.php
│   ├── Repositories/
│   │   ├── VisitorRepository.php
│   │   └── TestBatteryRepository.php
│   ├── Services/
│   │   ├── VisitorService.php
│   │   └── TestBatteryService.php
│   └── bootstrap.php
├── config/
│   └── database.php
├── database/
│   └── feira_iot.sql
├── docs/
│   ├── modelo-logico.md
│   ├── rotas-api.md
│   └── screenshots/
├── public/
│   ├── api/
│   │   └── index.php
│   ├── assets/
│   │   ├── css/styles.css
│   │   ├── js/site.js
│   │   ├── js/crud.js
│   │   └── images/
│   └── index.php
├── .gitignore
├── .htaccess
├── index.php
└── README.md
```

## Como executar com XAMPP

1. Copie a pasta `linebot-lina` para `C:\xampp\htdocs\linebot-lina`.
2. Abra o XAMPP e inicie **Apache** e **MySQL**.
3. Acesse `http://localhost/phpmyadmin`.
4. Importe o arquivo `database/feira_iot.sql`. Ele cria o banco `feira_iot` e as tabelas necessárias.
5. Confira as credenciais em `config/database.php`. No XAMPP padrão, o usuário é `root` e a senha fica vazia.
6. Acesse `http://localhost/linebot-lina/`.

A página inicial redireciona para `public/`. A pasta `public` é o diretório exposto ao navegador; os serviços, repositórios e arquivos de configuração ficam fora dela.

## Arquitetura baseada em serviços

O projeto separa responsabilidades:

- **Config:** cria e mantém a conexão PDO com MySQL.
- **Repositories:** concentram comandos SQL de leitura e manipulação dos registros.
- **Services:** validam dados e aplicam regras de negócio antes de chamar os repositórios.
- **API:** recebe métodos HTTP, seleciona a entidade e devolve respostas JSON.
- **Public:** contém a interface e os arquivos acessíveis pelo navegador.

## Banco de dados e CRUD

O banco `feira_iot` possui duas entidades principais:

1. `visitors`: nome, e-mail único, área de interesse e data de cadastro.
2. `test_batteries`: visitante associado (opcional), velocidade média, tempo da volta, status e data do teste.

A relação é de um visitante para muitos testes. A chave estrangeira usa `ON DELETE SET NULL`, de modo que excluir um visitante não apaga os testes já registrados.

O diagrama, os atributos e as regras de normalização estão em [`docs/modelo-logico.md`](docs/modelo-logico.md) e no arquivo visual [`docs/modelo-logico.svg`](docs/modelo-logico.svg). Os endpoints estão descritos em [`docs/rotas-api.md`](docs/rotas-api.md).

## Comentários no código

Os arquivos PHP e o SQL incluem comentários sobre a responsabilidade de cada camada, as regras de negócio, as operações CRUD, as chaves e os relacionamentos. O objetivo é facilitar a apresentação e a avaliação do algoritmo pela equipe e pelo professor.

## Capturas de tela para o GitHub

Antes de publicar o repositório, execute o projeto e adicione capturas reais em `docs/screenshots/`. Sugestão de imagens:

- `homepage.png` — apresentação e simulador;
- `arquitetura.png` — abas de serviços e persistência;
- `crud-visitantes.png` — criação, listagem, edição e exclusão de visitantes;
- `crud-testes.png` — CRUD das baterias de teste;
- `banco.png` — tabelas e relacionamento no phpMyAdmin.

Depois, adicione as imagens aqui usando, por exemplo:

```md
![Página inicial do LINA-01](docs/screenshots/homepage.png)
```

Não foram incluídas capturas fictícias: as imagens devem mostrar o projeto realmente executado no computador da equipe.

## Entrega no GitHub e YouTube

- **GitHub:** publique este diretório em um repositório público e atualize o link na seção “Entregáveis” do site.
- **YouTube:** grave a equipe apresentando o robô físico, o simulador, a arquitetura PHP, o banco relacional e o CRUD de ambas as entidades. Publique o vídeo e atualize o link na seção “Entregáveis”.

Os campos de URL da página são modelos para serem substituídos pelos endereços reais da equipe. Não envie links de exemplo como se fossem entregas concluídas.

## Observação sobre o dispositivo físico

O site apresenta o LINA-01 e a simulação de percurso. A integração para transmissão de dados reais do ESP32 não está ativada nesta versão, pois o foco da atividade entregue é apresentação, cadastro de visitantes, registro de testes e CRUD no banco de dados.
