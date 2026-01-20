<?php
declare(strict_types=1);

use App\Services\UsersService;
use App\Utils\Response;

// Variables disponibles desde public/index.php:
// - $dbCfg
// - $userId

$me = UsersService::getMe($dbCfg, (int)$userId);
Response::ok(['user' => $me]);
