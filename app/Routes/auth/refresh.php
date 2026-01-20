<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\AuthService;
use App\Services\AuditLogService;

/**
 * POST /auth/refresh
 * Body JSON:
 * {
 *   "refresh_token": "....",
 *   "device_id": "flutter-android" (opcional)
 * }
 */

$body = Request::json();

$refreshToken = (string)($body['refresh_token'] ?? '');
if (trim($refreshToken) === '') {
  Response::error('refresh_token requerido', 422);
}

$meta = [
  'device_id' => $body['device_id'] ?? null,
];

// Nunca guardes el refresh token en claro en auditoría
$rtHash = hash('sha256', $refreshToken);


try {
  $out = AuthService::refresh($dbCfg, $jwtCfg, $refreshCfg, $refreshToken, $meta);

  // user_id puede venir en la respuesta (si tu AuthService lo incluye)
  $auditUserId = null;
  if (is_array($out)) {
    if (isset($out['user']['id'])) $auditUserId = (int)$out['user']['id'];
    elseif (isset($out['user_id'])) $auditUserId = (int)$out['user_id'];
  }

  AuditLogService::log(
    $dbCfg,
    $auditUserId, // puede ser NULL
    'refresh',
    'auth',
    null,
    [
      'ok' => true,
      'rt_hash' => $rtHash,
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
    'refresh_failed',
    'auth',
    null,
    [
      'ok' => false,
      'rt_hash' => $rtHash,
      'device_id' => $meta['device_id'] ?? null,
      'method' => Request::method(),
      'path' => Request::path(),
      'error' => $debug ? $e->getMessage() : 'invalid_refresh',
    ]
  );
  Response::error($debug ? $e->getMessage() : 'Refresh token inválido', 401);
}