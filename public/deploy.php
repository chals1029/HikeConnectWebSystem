<?php

declare(strict_types=1);

/**
 * GitHub push webhook deploy endpoint.
 *
 * Configure GitHub:
 * - Payload URL: https://your-domain.com/deploy.php
 * - Content type: application/json
 * - Secret: same value as DEPLOY_WEBHOOK_SECRET in .env
 * - Event: Just the push event
 */

$root = dirname(__DIR__);
$logFile = $root.'/storage/logs/deploy-webhook.log';

function deploy_log(string $message, array $context = []): void
{
    global $logFile;

    $line = '['.date('Y-m-d H:i:s').'] '.$message;
    if ($context !== []) {
        $line .= ' '.json_encode($context, JSON_UNESCAPED_SLASHES);
    }
    $line .= PHP_EOL;

    @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

function deploy_response(int $status, array $payload): never
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function deploy_env(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }

    static $env = null;
    global $root;

    if ($env === null) {
        $env = [];
        $path = $root.'/.env';
        if (is_readable($path)) {
            foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
                    continue;
                }

                [$name, $raw] = explode('=', $line, 2);
                $raw = trim($raw);
                if (
                    (str_starts_with($raw, '"') && str_ends_with($raw, '"')) ||
                    (str_starts_with($raw, "'") && str_ends_with($raw, "'"))
                ) {
                    $raw = substr($raw, 1, -1);
                }
                $env[trim($name)] = $raw;
            }
        }
    }

    return $env[$key] ?? $default;
}

function deploy_run(string $command): array
{
    global $root;

    $descriptorSpec = [
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open($command, $descriptorSpec, $pipes, $root, null);
    if (! is_resource($process)) {
        return ['code' => 127, 'output' => 'Could not start command.'];
    }

    $stdout = stream_get_contents($pipes[1]) ?: '';
    $stderr = stream_get_contents($pipes[2]) ?: '';
    fclose($pipes[1]);
    fclose($pipes[2]);

    return [
        'code' => proc_close($process),
        'output' => trim($stdout."\n".$stderr),
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    deploy_response(405, ['success' => false, 'message' => 'POST only.']);
}

$secret = (string) deploy_env('DEPLOY_WEBHOOK_SECRET', '');
if ($secret === '') {
    deploy_log('Missing DEPLOY_WEBHOOK_SECRET');
    deploy_response(500, ['success' => false, 'message' => 'Webhook secret is not configured.']);
}

$payload = file_get_contents('php://input') ?: '';
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$expected = 'sha256='.hash_hmac('sha256', $payload, $secret);

if (! hash_equals($expected, $signature)) {
    deploy_log('Invalid webhook signature', ['ip' => $_SERVER['REMOTE_ADDR'] ?? null]);
    deploy_response(403, ['success' => false, 'message' => 'Invalid signature.']);
}

$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';
if ($event !== 'push') {
    deploy_response(202, ['success' => true, 'message' => 'Ignored non-push event.']);
}

$data = json_decode($payload, true);
if (! is_array($data)) {
    deploy_response(400, ['success' => false, 'message' => 'Invalid JSON payload.']);
}

$branch = (string) ($data['ref'] ?? '');
if ($branch !== 'refs/heads/main') {
    deploy_response(202, ['success' => true, 'message' => 'Ignored non-main branch.', 'ref' => $branch]);
}

$lockPath = $root.'/storage/framework/deploy-webhook.lock';
$lock = @fopen($lockPath, 'c');
if (! $lock || ! flock($lock, LOCK_EX | LOCK_NB)) {
    deploy_response(409, ['success' => false, 'message' => 'Deployment already running.']);
}

deploy_log('Deployment started', [
    'delivery' => $_SERVER['HTTP_X_GITHUB_DELIVERY'] ?? null,
    'after' => $data['after'] ?? null,
]);

$commands = [
    'git pull --ff-only origin main',
    'composer install --no-dev --optimize-autoloader --no-interaction',
    'php artisan migrate --force --no-interaction',
    'php artisan optimize:clear',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache',
];

$results = [];
foreach ($commands as $command) {
    $result = deploy_run($command);
    $results[] = [
        'command' => $command,
        'code' => $result['code'],
        'output' => mb_substr($result['output'], 0, 4000),
    ];

    deploy_log('Command finished', end($results));

    if ($result['code'] !== 0) {
        deploy_response(500, [
            'success' => false,
            'message' => 'Deployment command failed.',
            'failed_command' => $command,
            'log' => 'storage/logs/deploy-webhook.log',
        ]);
    }
}

deploy_log('Deployment completed');

deploy_response(200, [
    'success' => true,
    'message' => 'Deployment completed.',
]);
