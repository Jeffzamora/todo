<?php
declare(strict_types=1);

use App\Services\UsersService;
use App\Utils\Request;
use App\Utils\Response;

// Variables disponibles desde public/index.php:
// - $dbCfg
// - $refreshCfg
// - $userId

$data = Request::input();

$current = (string)($data['current_password'] ?? $data['currentPassword'] ?? '');
$next    = (string)($data['new_password'] ?? $data['newPassword'] ?? '');

if (trim($current) === '' || trim($next) === '') {
  Response::error('Debe enviar current_password y new_password', 422);
}

UsersService::changePassword($dbCfg, $refreshCfg, (int)$userId, $current, $next);

Response::ok(['message' => 'Contraseña actualizada. Vuelve a iniciar sesión en tus dispositivos.']);
