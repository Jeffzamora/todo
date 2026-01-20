<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\AuthService;
use App\Services\AuditLogService;

/**
 * POST /auth/logout
 * Body JSON:
 * {
 *   "refresh_token": "...."
 * }
 */

$body = Request::json();

$refreshToken = (string)($body['refresh_token'] ?? '');
if (trim($refreshToken) === '') {
  Response::error('refresh_token requerido', 422);
}

// Nunca guardes el refresh token en claro en auditoría
$rtHash = hash('sha256', $refreshToken);

try {
  // AuthService::logout requiere refreshCfg para poder hashear y revocar el token
  AuthService::logout($dbCfg, $refreshCfg, $refreshToken);

  AuditLogService::log(
    $dbCfg,
    null, // sin JWT aquí
    'logout',
    'auth',
    null,
    [
      'ok' => true,
      'rt_hash' => $rtHash,
      'method' => Request::method(),
      'path' => Request::path(),
    ]
  );

  Response::ok(['message' => 'logout ok']);
} catch (Throwable $e) {
  AuditLogService::log(
    $dbCfg,
    null,
    'logout_failed',
    'auth',
    null,
    [
      'ok' => false,
      'rt_hash' => $rtHash,
      'method' => Request::method(),
      'path' => Request::path(),
      'error' => $debug ? $e->getMessage() : 'logout_error',
    ]
  );
  Response::error($debug ? $e->getMessage() : 'Logout error', 400);
}