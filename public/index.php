<?php
declare(strict_types=1);
// Página pública de apresentação do protótipo LINA-01.
?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LINA-01 // Robô Seguidor de Linha IoT — Feira Tecnológica</title>
    <meta name="description" content="Conheça o LINA-01, robô seguidor de linha desenvolvido para a Feira Tecnológica. Explore a simulação, a arquitetura PHP e participe da experiência.">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Space Grotesk, Inter e JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        paper: '#F7F5F0',
                        surface: '#FFFFFF',
                        ink: '#141414',
                        muted: '#555555',
                        borderline: '#E5E2DC',
                        terracotta: '#C85A32',
                        terracottaDark: '#A84724',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body class="antialiased selection:bg-terracotta selection:text-white">

    <!-- Notificação Toast Flutuante (Substitui qualquer uso de alert) -->
    <div id="toastContainer" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>

    <!-- Cabeçalho Minimalista Fixo -->
    <header class="sticky top-0 z-40 bg-paper/90 backdrop-blur-md border-b border-borderline">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="#" class="flex items-center gap-3 group">
                <div class="w-8 h-8 rounded-lg bg-ink text-paper flex items-center justify-center font-mono text-xs font-semibold tracking-tighter group-hover:bg-terracotta transition-colors">
                    IR//01
                </div>
                <div>
                    <span class="font-display font-bold text-base tracking-tight block leading-none">LINA-01</span>
                    <span class="font-mono text-[10px] uppercase tracking-widest text-muted">Feira Tecnológica IoT</span>
                </div>
            </a>

            <!-- Navegação Desktop -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-muted">
                <a href="#arquitetura" class="hover:text-ink transition-colors">Arquitetura PHP & SOA</a>
                <a href="#visitantes-crud" class="hover:text-ink transition-colors">Experiência & CRUD</a>
                <a href="#modelagem" class="hover:text-ink transition-colors">Banco Relacional</a>
                <a href="#entregaveis" class="hover:text-ink transition-colors">Entregáveis</a>
            </nav>

            <!-- Botão de Ação Rápida -->
            <div class="flex items-center gap-3">
                <a href="#visitantes-crud" class="px-4 py-2 rounded-lg bg-ink text-paper text-xs sm:text-sm font-medium hover:bg-terracotta transition-colors">
                    Registrar Palpite
                </a>
                <!-- Botão Menu Mobile -->
                <button id="mobileMenuBtn" aria-label="Abrir menu" class="md:hidden p-2 rounded-lg border border-borderline text-ink hover:bg-surface">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu Mobile Expansível -->
        <div id="mobileMenu" class="hidden md:hidden bg-surface border-b border-borderline px-4 py-4 space-y-3">
            <a href="#arquitetura" class="block text-sm font-medium text-ink py-1">Arquitetura PHP & SOA</a>
            <a href="#visitantes-crud" class="block text-sm font-medium text-ink py-1">Experiência do Visitante (CRUD)</a>
            <a href="#modelagem" class="block text-sm font-medium text-ink py-1">Modelagem & Código PHP</a>
            <a href="#entregaveis" class="block text-sm font-medium text-ink py-1">Entregáveis da Equipe</a>
        </div>
    </header>

    <!-- 1. HERO SECTION EDITORIAL COM SIMULADOR DE TRAJETÓRIA INTERATIVO -->
    <section class="relative pt-12 pb-20 lg:pt-20 lg:pb-28 border-b border-borderline swiss-grid-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Coluna Editorial (Esquerda) -->
                <div class="lg:col-span-6 space-y-6 reveal active">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface border border-borderline text-xs font-mono text-muted">
                        <span class="w-2 h-2 rounded-full bg-terracotta sensor-active"></span>
                        <span>EXPOSIÇÃO INTERATIVA // SISTEMAS EMBARCADOS & WEB</span>
                    </div>

                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-ink leading-[1.08]">
                        Precisão óptica conectada a serviços web.
                    </h1>

                    <p class="text-base sm:text-lg text-muted leading-relaxed max-w-xl">
                        Conheça o <strong class="font-semibold text-ink">LINA-01</strong>, nosso protótipo de Robô Seguidor de Linha IoT desenvolvido para a Feira Tecnológica. O projeto une leitura infravermelha em malha fechada com uma aplicação web em <strong class="font-semibold text-ink">PHP orientada a serviços</strong> e banco de dados relacional para registrar baterias de teste e a participação dos visitantes.
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <a href="#visitantes-crud" class="px-6 py-3.5 rounded-xl bg-ink text-paper text-sm font-medium hover:bg-terracotta transition-all shadow-sm">
                            Participar do Desafio na Feira
                        </a>
                        <a href="#arquitetura" class="px-6 py-3.5 rounded-xl bg-surface border border-borderline text-ink text-sm font-medium hover:border-ink transition-all">
                            Explorar Arquitetura SOA
                        </a>
                    </div>

                    <!-- Especificações Técnicas Resumidas -->
                    <div class="pt-6 grid grid-cols-3 gap-4 border-t border-borderline">
                        <div>
                            <span class="font-mono text-xs text-muted block">SENSORES</span>
                            <span class="font-display font-semibold text-lg text-ink">2 sensores TCRT5000</span>
                        </div>
                        <div>
                            <span class="font-mono text-xs text-muted block">BACKEND</span>
                            <span class="font-display font-semibold text-lg text-ink">PHP 8 REST/SOA</span>
                        </div>
                        <div>
                            <span class="font-mono text-xs text-muted block">PERSISTÊNCIA</span>
                            <span class="font-display font-semibold text-lg text-ink">MySQL Relacional</span>
                        </div>
                    </div>
                </div>

                <!-- Coluna Interativa: Pista Vetorial & Robô Seguidor de Linha (Direita) -->
                <div class="lg:col-span-6 reveal active reveal-delay-1">
                    <div class="bg-surface rounded-2xl border border-borderline p-4 sm:p-6 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-2 pb-4 mb-4 border-b border-borderline">
                            <div>
                                <span class="font-mono text-[11px] uppercase tracking-wider text-terracotta font-semibold block">
                                    Demonstração Interativa
                                </span>
                                <h2 class="font-display font-semibold text-base text-ink">
                                    Simule o percurso do LINA-01
                                </h2>
                            </div>
                            <div class="flex items-center gap-2">
                                <button id="btnTrackPreset1" class="px-2.5 py-1 rounded-md text-xs font-mono bg-paper border border-borderline hover:border-ink transition-colors">
                                    Circuito S
                                </button>
                                <button id="btnTrackPreset2" class="px-2.5 py-1 rounded-md text-xs font-mono bg-paper border border-borderline hover:border-ink transition-colors">
                                    Oval Técnico
                                </button>
                                <button id="btnClearTrack" class="px-2.5 py-1 rounded-md text-xs font-mono bg-ink text-paper hover:bg-terracotta transition-colors">
                                    Limpar
                                </button>
                            </div>
                        </div>

                        <!-- Container Responsivo do Canvas -->
                        <div id="trackCanvasWrapper" class="relative w-full h-72 sm:h-80 rounded-xl bg-paper border border-borderline overflow-hidden cursor-crosshair touch-none">
                            <canvas id="robotCanvas" class="w-full h-full block"></canvas>
                            
                            <!-- Legenda Discreta no Canto do Canvas -->
                            <div class="absolute bottom-3 left-3 bg-surface/95 backdrop-blur-sm border border-borderline rounded-lg px-3 py-1.5 text-[11px] font-mono text-muted pointer-events-none">
                                Selecione um circuito ou desenhe seu próprio trajeto
                            </div>
                        </div>

                        <!-- Barra de Leitura dos 5 Sensores Infravermelhos em Tempo Real -->
                        <div class="mt-4 pt-4 border-t border-borderline flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="font-mono text-[11px] text-muted block mb-1">SENSORES INFRAVERMELHOS // ESQUERDO E DIREITO</span>
                                <div id="sensorBars" class="flex items-center gap-2">
                                    <div class="flex items-center gap-2 rounded-lg border border-borderline bg-paper px-3 py-2">
                                        <span class="font-mono text-[10px] text-muted">ESQ.</span>
                                        <span class="w-8 h-5 rounded bg-ink text-paper flex items-center justify-center font-mono text-[10px] font-bold sensor-box sensor-active" data-index="0">1</span>
                                    </div>
                                    <div class="flex items-center gap-2 rounded-lg border border-borderline bg-paper px-3 py-2">
                                        <span class="font-mono text-[10px] text-muted">DIR.</span>
                                        <span class="w-8 h-5 rounded bg-paper border border-borderline flex items-center justify-center font-mono text-[10px] text-muted sensor-box" data-index="1">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <span class="font-mono text-[11px] text-muted block">CORREÇÃO PID</span>
                                    <span id="pidValue" class="font-mono text-xs font-semibold text-ink">Kp: 2.15 | Ki: 0.00</span>
                                </div>
                                <button id="btnToggleSim" class="px-3 py-2 rounded-lg bg-paper border border-borderline text-xs font-medium text-ink hover:border-ink transition-colors">
                                    Pausar Robô
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. ARQUITETURA PHP BASEADA EM SERVIÇOS (SOA) -->
    <section id="arquitetura" class="py-20 bg-surface border-b border-borderline">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="max-w-3xl mb-12 reveal">
                <span class="font-mono text-xs uppercase tracking-widest text-terracotta font-semibold block mb-2">
                    Arquitetura de Software // PHP 8
                </span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold tracking-tight text-ink">
                    Arquitetura PHP baseada em serviços
                </h2>
                <p class="mt-3 text-muted text-base leading-relaxed">
                    Para atender aos requisitos técnicos da Feira Tecnológica, a aplicação web divide-se em serviços desacoplados em PHP puro. O ecossistema garante segurança, validação de requisições HTTP e separação entre serviços, repositórios e conexão PDO com o MySQL.
                </p>
            </div>

            <!-- Abas Interativas de Arquitetura -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start reveal reveal-delay-1">
                
                <!-- Menu de Seleção de Camadas -->
                <div class="lg:col-span-4 space-y-2">
                    <button onclick="switchArchTab('service')" id="tabBtn-service" class="w-full text-left p-4 rounded-xl border border-ink bg-ink text-paper transition-all flex items-center justify-between group">
                        <div>
                            <span class="font-mono text-xs opacity-80 block">01 // Camada Lógica</span>
                            <span class="font-display font-bold text-base block mt-0.5">VisitorService.php</span>
                        </div>
                        <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <button onclick="switchArchTab('repo')" id="tabBtn-repo" class="w-full text-left p-4 rounded-xl border border-borderline bg-paper text-ink hover:border-ink transition-all flex items-center justify-between group">
                        <div>
                            <span class="font-mono text-xs text-muted block">02 // Camada de Dados</span>
                            <span class="font-display font-bold text-base block mt-0.5">TestBatteryRepository.php</span>
                        </div>
                        <svg class="w-5 h-5 text-muted group-hover:text-ink transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <button onclick="switchArchTab('db')" id="tabBtn-db" class="w-full text-left p-4 rounded-xl border border-borderline bg-paper text-ink hover:border-ink transition-all flex items-center justify-between group">
                        <div>
                            <span class="font-mono text-xs text-muted block">03 // Conexão PDO</span>
                            <span class="font-display font-bold text-base block mt-0.5">Database.php (Singleton)</span>
                        </div>
                        <svg class="w-5 h-5 text-muted group-hover:text-ink transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <!-- Bloco de Exibição de Código PHP Comentado -->
                <div class="lg:col-span-8 bg-paper rounded-2xl border border-borderline p-6 font-mono text-xs sm:text-sm text-ink overflow-x-auto shadow-sm">
                    
                    <!-- Aba 1: Service -->
                    <div id="archTab-service" class="space-y-3">
                        <div class="flex items-center justify-between pb-3 border-b border-borderline text-muted text-[11px]">
                            <span>app/Services/VisitorService.php</span>
                            <span>PHP 8.2 // Strict Types</span>
                        </div>
                        <pre class="text-ink leading-relaxed overflow-x-auto"><code>&lt;?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\VisitorRepository;

// Serviço responsável por validar e cadastrar visitantes.
final class VisitorService
{
    public function __construct(private VisitorRepository $repository) {}

    public function create(array $input): int
    {
        $email = trim((string) ($input['email'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('E-mail inválido.');
        }

        return $this-&gt;repository-&gt;create([
            'name' =&gt; trim((string) $input['name']),
            'email' =&gt; $email,
            'interest' =&gt; trim((string) $input['interest']),
        ]);
    }
}</code></pre>
                    </div>

                    <!-- Aba 2: Repository -->
                    <div id="archTab-repo" class="space-y-3 hidden">
                        <div class="flex items-center justify-between pb-3 border-b border-borderline text-muted text-[11px]">
                            <span>app/Repositories/TestBatteryRepository.php</span>
                            <span>CRUD Completo // Prepared Statements</span>
                        </div>
                        <pre class="text-ink leading-relaxed overflow-x-auto"><code>&lt;?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

// Repositório concentra o acesso SQL à entidade de testes.
final class TestBatteryRepository
{
    public function __construct(private PDO $db) {}

    // CREATE: grava um novo teste usando parâmetros preparados.
    public function create(array $data): int
    {
        $sql = 'INSERT INTO test_batteries
                (visitor_id, avg_speed, lap_time, status)
                VALUES (:visitor_id, :avg_speed, :lap_time, :status)';
        $statement = $this-&gt;db-&gt;prepare($sql);
        $statement-&gt;execute($data);
        return (int) $this-&gt;db-&gt;lastInsertId();
    }

    // READ, UPDATE e DELETE também ficam nesta camada.
}</code></pre>
                    </div>

                    <!-- Aba 3: Database -->
                    <div id="archTab-db" class="space-y-3 hidden">
                        <div class="flex items-center justify-between pb-3 border-b border-borderline text-muted text-[11px]">
                            <span>app/Config/Database.php</span>
                            <span>Singleton PDO & Tratamento de Erros</span>
                        </div>
                        <pre class="text-ink leading-relaxed overflow-x-auto"><code>&lt;?php
declare(strict_types=1);

namespace App\Config;

use PDO;

// A conexão é criada uma vez e compartilhada pelos repositórios.
final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";
            self::$connection = new PDO($dsn, $config['username'], $config['password']);
        }
        return self::$connection;
    }
}</code></pre>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- 3. SISTEMA CRUD COMPLETO E INTERATIVO (DUAS ENTIDADES: VISITANTES & BATERIAS DE TESTE) -->
    <section id="visitantes-crud" class="py-20 swiss-grid-bg border-b border-borderline">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 reveal">
                <div>
                    <span class="font-mono text-xs uppercase tracking-widest text-terracotta font-semibold block mb-2">
                        Ciclo de Vida de Dados // Operações CRUD
                    </span>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold tracking-tight text-ink">
                        Gestão de Visitantes & Baterias de Teste na Feira
                    </h2>
                    <p class="mt-3 text-muted text-base max-w-2xl leading-relaxed">
                        Teste abaixo o funcionamento completo das duas entidades obrigatórias (<strong class="text-ink font-medium">visitantes</strong> e <strong class="text-ink font-medium">baterias de teste</strong>). Os formulários abaixo fazem requisições assíncronas reais à API PHP e gravam os dados no MySQL.
                    </p>
                </div>

                <!-- Seletor de Entidade CRUD -->
                <div class="flex items-center p-1 bg-surface rounded-xl border border-borderline">
                    <button onclick="switchEntity('visitors')" id="entityBtn-visitors" class="px-4 py-2 rounded-lg text-xs font-mono font-medium bg-ink text-paper transition-all">
                        Entidade: Visitantes
                    </button>
                    <button onclick="switchEntity('batteries')" id="entityBtn-batteries" class="px-4 py-2 rounded-lg text-xs font-mono font-medium text-muted hover:text-ink transition-all">
                        Entidade: Baterias (Testes)
                    </button>
                </div>
            </div>

            <!-- Grid do CRUD Interativo -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Formulário de Cadastro / Edição (Esquerda) -->
                <div class="lg:col-span-5 bg-surface rounded-2xl border border-borderline p-6 shadow-sm reveal reveal-delay-1">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-borderline">
                        <h3 id="formTitle" class="font-display font-bold text-lg text-ink">Novo Cadastro de Visitante</h3>
                        <span id="formModeBadge" class="px-2.5 py-1 rounded-md text-[10px] font-mono bg-paper text-muted border border-borderline">Modo: Create (INSERT)</span>
                    </div>

                    <!-- Formulário Dinâmico para Visitantes -->
                    <form id="crudForm" onsubmit="handleCrudSubmit(event)" class="space-y-4">
                        <input type="hidden" id="editRecordId" value="">
                        
                        <div id="fieldGroup-name">
                            <label class="block font-mono text-xs text-muted mb-1.5">NOME COMPLETO DO VISITANTE</label>
                            <input type="text" id="inputName" required placeholder="Ex: Camila Souza" class="w-full px-4 py-3 rounded-xl bg-paper border border-borderline text-ink text-sm focus:outline-none focus:border-ink transition-colors">
                        </div>

                        <div id="fieldGroup-email">
                            <label class="block font-mono text-xs text-muted mb-1.5">E-MAIL INSTITUCIONAL OU PESSOAL</label>
                            <input type="email" id="inputEmail" required placeholder="camila@escola.br" class="w-full px-4 py-3 rounded-xl bg-paper border border-borderline text-ink text-sm focus:outline-none focus:border-ink transition-colors">
                        </div>

                        <div id="fieldGroup-extra" class="hidden space-y-4">
                            <div>
                                <label class="block font-mono text-xs text-muted mb-1.5">TEMPO DA VOLTA (SEGUNDOS) / VELOCIDADE MÉDIA (M/S)</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="number" min="0.01" step="0.01" id="inputTime" required placeholder="Ex: 14.85 s" class="w-full px-4 py-3 rounded-xl bg-paper border border-borderline text-ink text-sm focus:outline-none focus:border-ink transition-colors">
                                    <input type="number" min="0" step="0.01" id="inputSpeed" required placeholder="Ex: 1.25 m/s" class="w-full px-4 py-3 rounded-xl bg-paper border border-borderline text-ink text-sm focus:outline-none focus:border-ink transition-colors">
                                </div>
                            </div>
                            <div>
                                <label class="block font-mono text-xs text-muted mb-1.5">VISITANTE ASSOCIADO (OPCIONAL)</label>
                                <select id="inputVisitor" class="w-full px-4 py-3 rounded-xl bg-paper border border-borderline text-ink text-sm focus:outline-none focus:border-ink transition-colors">
                                    <option value="">Sem visitante associado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-mono text-xs text-muted mb-1.5">STATUS DO TESTE</label>
                                <select id="inputStatus" class="w-full px-4 py-3 rounded-xl bg-paper border border-borderline text-ink text-sm focus:outline-none focus:border-ink transition-colors">
                                    <option value="Concluído">Concluído</option>
                                    <option value="Recorde da Feira">Recorde da Feira</option>
                                    <option value="Interrompido">Interrompido</option>
                                </select>
                            </div>
                        </div>

                        <div id="fieldGroup-interest">
                            <label class="block font-mono text-xs text-muted mb-1.5">ÁREA DE INTERESSE / CURSO</label>
                            <select id="inputInterest" required class="w-full px-4 py-3 rounded-xl bg-paper border border-borderline text-ink text-sm focus:outline-none focus:border-ink transition-colors">
                                <option value="Engenharia e Eletrônica">Engenharia e Eletrônica</option>
                                <option value="Sistemas IoT & Web PHP">Sistemas IoT & Web PHP</option>
                                <option value="Inteligência Artificial">Inteligência Artificial</option>
                                <option value="Visitante Geral da Feira">Visitante Geral da Feira</option>
                            </select>
                        </div>

                        <div class="pt-2 flex items-center gap-3">
                            <button type="submit" id="submitBtn" class="flex-1 py-3.5 rounded-xl bg-ink text-paper text-sm font-medium hover:bg-terracotta transition-colors shadow-sm">
                                Salvar Registro (CREATE)
                            </button>
                            <button type="button" onclick="resetCrudForm()" id="cancelEditBtn" class="hidden px-4 py-3.5 rounded-xl bg-paper border border-borderline text-ink text-sm font-medium hover:bg-borderline transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </form>

                    <!-- Preview da Requisição PHP / SQL -->
                    <div class="mt-6 pt-6 border-t border-borderline">
                        <span class="font-mono text-[10px] text-muted uppercase tracking-wider block mb-2">Última operação enviada à API PHP</span>
                        <div id="sqlPreview" class="p-3 rounded-lg bg-paper font-mono text-[11px] text-muted overflow-x-auto border border-borderline">
                            { "operation": "POST", "entity": "visitors" }
                        </div>
                    </div>
                </div>

                <!-- Tabela de Registros Existentes (Direita) -->
                <div class="lg:col-span-7 bg-surface rounded-2xl border border-borderline p-6 shadow-sm reveal reveal-delay-2">
                    <div class="flex flex-wrap items-center justify-between gap-4 pb-4 mb-4 border-b border-borderline">
                        <div>
                            <h3 id="tableTitle" class="font-display font-bold text-lg text-ink">Registros na Base de Dados</h3>
                            <span id="recordCount" class="font-mono text-xs text-muted">Carregando registros do MySQL...</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" id="searchInput" oninput="filterRecords()" placeholder="Pesquisar..." class="px-3 py-1.5 rounded-lg bg-paper border border-borderline text-xs text-ink focus:outline-none focus:border-ink">
                        </div>
                    </div>

                    <!-- Lista de Registros -->
                    <div id="recordsContainer" class="space-y-3 max-h-[420px] overflow-y-auto pr-1">
                        <!-- Renderizado via JavaScript dinâmico -->
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- 4. MODELAGEM RELACIONAL E BANCO DE DADOS -->
    <section id="modelagem" class="py-20 bg-surface border-b border-borderline">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="max-w-3xl mb-12 reveal">
                <span class="font-mono text-xs uppercase tracking-widest text-terracotta font-semibold block mb-2">
                    Modelagem de Banco de Dados Relacional
                </span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold tracking-tight text-ink">
                    Esquema Lógico com Comentários Estruturados
                </h2>
                <p class="mt-3 text-muted text-base leading-relaxed">
                    O banco MySQL utiliza chaves primárias (PK) e estrangeiras (FK) para relacionar visitantes com as baterias de testes do robô. Os comandos exibidos correspondem ao arquivo SQL incluído no projeto.
                </p>
            </div>

            <!-- Diagrama Relacional / Código SQL Comentado -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center reveal reveal-delay-1">
                
                <!-- Código SQL Completo com Comentários -->
                <div class="lg:col-span-7 bg-paper rounded-2xl border border-borderline p-6 font-mono text-xs sm:text-sm text-ink overflow-x-auto shadow-sm">
                    <div class="flex items-center justify-between pb-3 border-b border-borderline text-muted text-[11px] mb-4">
                        <span>database/feira_iot.sql</span>
                        <span>MySQL 8.0 // Relacional</span>
                    </div>
                    <pre class="text-ink leading-relaxed overflow-x-auto"><code>-- Banco relacional LINA-01. O arquivo database/feira_iot.sql contém o script completo.
CREATE DATABASE IF NOT EXISTS feira_iot CHARACTER SET utf8mb4;
USE feira_iot;

-- Entidade 1: visitantes da Feira Tecnológica.
CREATE TABLE visitors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    interest VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Entidade 2: registros de testes feitos pelo robô.
CREATE TABLE test_batteries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    visitor_id INT UNSIGNED NULL,
    avg_speed DECIMAL(5,2) NOT NULL,
    lap_time DECIMAL(6,2) NOT NULL,
    status ENUM('Concluído', 'Recorde da Feira', 'Interrompido') NOT NULL DEFAULT 'Concluído',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (visitor_id) REFERENCES visitors(id) ON DELETE SET NULL
) ENGINE=InnoDB;</code></pre>
                </div>

                <!-- Explicação Visual das Entidades -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-paper rounded-2xl border border-borderline p-6">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-ink text-paper flex items-center justify-center font-mono text-xs font-bold">1</div>
                            <h4 class="font-display font-bold text-base text-ink">Entidade `visitors`</h4>
                        </div>
                        <p class="text-sm text-muted leading-relaxed">
                            Armazena os dados dos avaliadores e visitantes da feira, garantindo integridade através de restrições de unicidade (`UNIQUE`) no e-mail e validação de formato.
                        </p>
                    </div>

                    <div class="bg-paper rounded-2xl border border-borderline p-6">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-terracotta text-white flex items-center justify-center font-mono text-xs font-bold">2</div>
                            <h4 class="font-display font-bold text-base text-ink">Entidade `test_batteries`</h4>
                        </div>
                        <p class="text-sm text-muted leading-relaxed">
                            Registra o desempenho métrico do robô seguidor de linha em cada teste de pista, conectado em relacionamento 1 para N com os visitantes que acompanharam a demonstração.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- 5. ENTREGÁVEIS OBRIGATÓRIOS (GITHUB & YOUTUBE) -->
    <section id="entregaveis" class="py-20 swiss-grid-bg border-b border-borderline">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="max-w-3xl mb-12 reveal">
                <span class="font-mono text-xs uppercase tracking-widest text-terracotta font-semibold block mb-2">
                    Documentação & Apresentação Oficial
                </span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold tracking-tight text-ink">
                    Entregáveis Obrigatórios para a Feira Tecnológica
                </h2>
                <p class="mt-3 text-muted text-base leading-relaxed">
                    Depois de publicar o repositório e enviar o vídeo, substitua os endereços de exemplo pelos links oficiais da equipe.
                </p>
            </div>

            <!-- Cards de Entregáveis -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 reveal reveal-delay-1">
                
                <!-- Card GitHub -->
                <div class="bg-surface rounded-2xl border border-borderline p-8 shadow-sm flex flex-col justify-between group hover:border-ink transition-all">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-12 h-12 rounded-xl bg-ink text-paper flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                            </div>
                            <span class="font-mono text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-md bg-paper text-muted border border-borderline">Requisito 01</span>
                        </div>

                        <h3 class="font-display font-bold text-xl text-ink mb-2">Repositório Público no GitHub</h3>
                        <p class="text-sm text-muted leading-relaxed mb-6">
                            Código-fonte completo organizado em serviços PHP, repositórios, interface pública, API, banco SQL e README com instalação, modelo lógico e espaço para capturas de tela.
                        </p>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-borderline">
                        <div class="flex items-center gap-2">
                            <input type="text" id="githubInputUrl" value="https://github.com/VictorRossi-Developer/RoboSeguidorDeLinha" class="w-full px-3 py-2 rounded-lg bg-paper border border-borderline font-mono text-xs text-ink focus:outline-none">
                            <button onclick="copyToClipboard('githubInputUrl')" class="px-3 py-2 rounded-lg bg-ink text-paper font-mono text-xs hover:bg-terracotta transition-colors">Copiar</button>
                        </div>
                        <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="block w-full py-3 text-center rounded-xl bg-ink text-paper text-sm font-medium hover:bg-terracotta transition-colors">
                            Acessar Repositório no GitHub
                        </a>
                    </div>
                </div>

                <!-- Card YouTube -->
                <div class="bg-surface rounded-2xl border border-borderline p-8 shadow-sm flex flex-col justify-between group hover:border-ink transition-all">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-12 h-12 rounded-xl bg-terracotta text-white flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </div>
                            <span class="font-mono text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-md bg-paper text-muted border border-borderline">Requisito 02</span>
                        </div>

                        <h3 class="font-display font-bold text-xl text-ink mb-2">Vídeo de Apresentação no YouTube</h3>
                        <p class="text-sm text-muted leading-relaxed mb-6">
                            Gravação completa da equipe apresentando o funcionamento do dispositivo IoT na pista, a arquitetura PHP baseada em serviços e a manipulação do banco de dados relacional.
                        </p>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-borderline">
                        <div class="flex items-center gap-2">
                            <input type="text" id="youtubeInputUrl" value="https://youtube.com/" class="w-full px-3 py-2 rounded-lg bg-paper border border-borderline font-mono text-xs text-ink focus:outline-none">
                            <button onclick="copyToClipboard('youtubeInputUrl')" class="px-3 py-2 rounded-lg bg-ink text-paper font-mono text-xs hover:bg-terracotta transition-colors">Copiar</button>
                        </div>
                        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="block w-full py-3 text-center rounded-xl bg-ink text-paper text-sm font-medium hover:bg-terracotta transition-colors">
                            Assistir Vídeo no YouTube
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Rodapé Editorial -->
    <footer class="bg-paper py-12 border-t border-borderline">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-ink text-paper flex items-center justify-center font-mono text-xs font-semibold">
                    IR//01
                </div>
                <div>
                    <span class="font-display font-bold text-sm block">Robô Seguidor de Linhas IoT</span>
                    <span class="font-mono text-xs text-muted">Feira Tecnológica — Aplicação Web SOA & CRUD</span>
                </div>
            </div>
<div class="font-mono text-xs text-muted text-center md:text-right">
    Desenvolvido por: <br><br>
    Arthur Ozório Silva<br>
    Julia Prado de Lira<br>
    Melissa Martins dos Santos<br>
    Victor Yudi Rossi Marimoto<br>
</div>

        </div>
    </footer>

    <script src="./assets/js/site.js" defer></script>
    <script src="./assets/js/crud.js" defer></script>
</body>
</html>
