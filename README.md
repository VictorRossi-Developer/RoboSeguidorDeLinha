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

## Banco de dados e CRUD

O banco `feira_iot` possui duas entidades principais:

1. `visitors`: nome, e-mail único, área de interesse e data de cadastro.
2. `test_batteries`: visitante associado (opcional), velocidade média, tempo da volta, status e data do teste.

A relação é de um visitante para muitos testes. A chave estrangeira usa `ON DELETE SET NULL`, de modo que excluir um visitante não apaga os testes já registrados.

## Comentários no código

Os arquivos PHP e o SQL incluem comentários sobre a responsabilidade de cada camada, as regras de negócio, as operações CRUD, as chaves e os relacionamentos. O objetivo é facilitar a apresentação e a avaliação do algoritmo pela equipe e pelo professor.

## GitHub e YouTube

- **GitHub:** [Repositório oficial](https://github.com/VictorRossi-Developer/RoboSeguidorDeLinha).
- **YouTube:** Link em breve.
