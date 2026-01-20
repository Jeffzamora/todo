<?php
declare(strict_types=1);


use App\Utils\Request;
use App\Utils\Response;
use App\Services\AuthService;
use App\Services\AuditLogService;

/**
 * POST /auth/login
 * Body JSON:
 * {
 *   "identifier": "username o email",
 *   "password": "123456",
 *   "device_id": "flutter-android" (opcional)
 * }
 */

$body = Request::json();

$identifier = trim((string)($body['identifier'] ?? ''));
$password   = (string)($body['password'] ?? '');

if ($identifier === '' || $password === '') {
  Response::error('identifier y password son requeridos', 422);
}

$meta = [
  'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
  'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
  'device_id' => $body['device_id'] ?? null,
];

try {
  $out = AuthService::login($dbCfg, $jwtCfg, $refreshCfg, $identifier, $password, $meta);

  $auditUserId = null;
  if (is_array($out) && isset($out['user']['id'])) {
    $auditUserId = (int)$out['user']['id'];
  }

  AuditLogService::log(
    $dbCfg,
    $auditUserId,
    'login',
    'auth',
    null,
    [
      'ok' => true,
      'identifier' => $identifier,
      'device_id' => $meta['device_id'] ?? null,
      'method' => Request::method(),
      'path' => Request::path(),
    ]
  );

  Response::ok($out);
} catch (Throwable $e) {
  AuditLogService::log(
    $dbCfg,
    null,
    'login_failed',
    'auth',
    null,
    [
      'ok' => false,
      'identifier' => $identifier,
      'device_id' => $meta['device_id'] ?? null,
      'method' => Request::method(),
      'path' => Request::path(),
      'error' => $debug ? $e->getMessage() : 'invalid_credentials',
    ]
  );

  // No filtrar detalles en prod
  Response::error($debug ? $e->getMessage() : 'Credenciales inválidas', 401);
}