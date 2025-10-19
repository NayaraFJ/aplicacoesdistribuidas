<?php

declare(strict_types=1);

function getBaseUrl(): string
{
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? null) === '443');
    $scheme = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    $scriptDir = str_replace('\\', '/', $scriptDir);
    if ($scriptDir === '/' || $scriptDir === '.') {
        $scriptDir = '';
    }

    $scriptDir = trim($scriptDir, '/');
    $basePath = $scriptDir === '' ? '' : $scriptDir . '/';

    return sprintf('%s://%s/%s', $scheme, $host, $basePath);
}

function callApi(string $endpoint, string $method = 'GET', array $data = []): array
{
    $url = getBaseUrl() . ltrim($endpoint, '/');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    }

    $body = curl_exec($ch);
    $error = curl_error($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'url' => $url,
        'status' => $statusCode ?: 0,
        'body' => $body === false ? '' : $body,
        'error' => $error ?: null,
    ];
}

function formatJson(?string $payload): string
{
    if ($payload === null || $payload === '') {
        return '';
    }

    $decoded = json_decode($payload, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $payload;
    }

    return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

function registrarOperacao(array &$registro, string $titulo, array $resultado): void
{
    $registro[] = [
        'titulo' => $titulo,
        'url' => $resultado['url'],
        'status' => $resultado['status'],
        'error' => $resultado['error'],
        'body' => formatJson($resultado['body']),
    ];
}

$operacoes = [];
$idsInseridos = [];

$registrosParaCriar = [
    [
        'nome' => 'Exemplo Dinâmico 1',
        'email' => 'exemplo1@dominio.com',
        'celular' => '31999990001',
        'data_nascimento' => '1990-01-10',
    ],
    [
        'nome' => 'Exemplo Dinâmico 2',
        'email' => 'exemplo2@dominio.com',
        'celular' => '31999990002',
        'data_nascimento' => '1991-02-15',
    ],
    [
        'nome' => 'Exemplo Dinâmico 3',
        'email' => 'exemplo3@dominio.com',
        'celular' => '31999990003',
        'data_nascimento' => '1992-03-20',
    ],
    [
        'nome' => 'Exemplo Dinâmico 4',
        'email' => 'exemplo4@dominio.com',
        'celular' => '31999990004',
        'data_nascimento' => '1993-04-25',
    ],
];

foreach ($registrosParaCriar as $indice => $payload) {
    $resultado = callApi('add_dados_pessoais.php', 'POST', $payload);
    registrarOperacao($operacoes, sprintf('Inclusão %d', $indice + 1), $resultado);

    $dados = json_decode($resultado['body'] ?? '', true);
    if (json_last_error() === JSON_ERROR_NONE && isset($dados['matricula'])) {
        $idsInseridos[] = (int) $dados['matricula'];
    }
}

registrarOperacao($operacoes, 'Listagem após inclusões', callApi('get_dados_pessoais.php'));

$idsParaExcluir = [];
if (isset($idsInseridos[0])) {
    $idsParaExcluir[] = $idsInseridos[0];
}
if (isset($idsInseridos[2])) {
    $idsParaExcluir[] = $idsInseridos[2];
}

foreach ($idsParaExcluir as $indice => $matricula) {
    $resultado = callApi('delete_dados_pessoais.php', 'POST', ['matricula' => $matricula]);
    registrarOperacao($operacoes, sprintf('Exclusão %d (matrícula %d)', $indice + 1, $matricula), $resultado);
}

registrarOperacao($operacoes, 'Listagem após exclusões', callApi('get_dados_pessoais.php'));

$atualizacoes = [];
if (isset($idsInseridos[1])) {
    $atualizacoes[] = [
        'matricula' => $idsInseridos[1],
        'nome' => 'Exemplo Atualizado 2',
        'email' => 'exemplo2@dominio.com',
        'celular' => '31999991002',
        'data_nascimento' => '1991-02-20',
    ];
}
if (isset($idsInseridos[3])) {
    $atualizacoes[] = [
        'matricula' => $idsInseridos[3],
        'nome' => 'Exemplo Atualizado 4',
        'email' => 'exemplo4@dominio.com',
        'celular' => '31999991004',
        'data_nascimento' => '1993-04-30',
    ];
}

foreach ($atualizacoes as $indice => $payload) {
    $resultado = callApi('update_dados_pessoais.php', 'POST', $payload);
    registrarOperacao($operacoes, sprintf('Atualização %d (matrícula %d)', $indice + 1, $payload['matricula']), $resultado);
}

registrarOperacao($operacoes, 'Listagem final após atualizações', callApi('get_dados_pessoais.php'));

?><!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Demostração Dinâmica de APIs - Dados Pessoais</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .operation-card {
            margin-bottom: 1.5rem;
        }
        pre {
            white-space: pre-wrap;
            word-break: break-word;
            background-color: #212529;
            color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body class="py-4">
<div class="container">
    <header class="mb-4">
        <h1 class="display-6">Demonstração de Consumo das APIs de Dados Pessoais</h1>
        <p class="lead">Este roteiro executa sequencialmente operações de criação, listagem, exclusão e atualização utilizando as APIs REST internas.</p>
        <p class="text-muted mb-0">Todas as requisições são realizadas pelo próprio servidor, respeitando a limitação de origem única do ambiente de hospedagem.</p>
    </header>

    <?php foreach ($operacoes as $operacao): ?>
        <div class="card shadow-sm operation-card">
            <div class="card-header">
                <strong><?php echo htmlspecialchars($operacao['titulo'], ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Endpoint:</strong> <?php echo htmlspecialchars($operacao['url'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="mb-1"><strong>Status HTTP:</strong> <?php echo (int) $operacao['status']; ?></p>
                <?php if (!empty($operacao['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        Erro na requisição: <?php echo htmlspecialchars($operacao['error'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($operacao['body'] !== ''): ?>
                    <pre><?php echo htmlspecialchars($operacao['body'], ENT_NOQUOTES, 'UTF-8'); ?></pre>
                <?php else: ?>
                    <p class="text-muted mb-0">Nenhum conteúdo retornado pela API.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
