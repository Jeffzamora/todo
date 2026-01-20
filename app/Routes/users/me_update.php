<?php
declare(strict_types=1);

use App\Services\UsersService;
use App\Utils\Request;
use App\Utils\Response;

// Variables disponibles desde public/index.php:
// - $dbCfg
// - $userId

$data = Request::input();

$updated = UsersService::updateMe($dbCfg, (int)$userId, $data);
Response::ok(['user' => $updated]);
