<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\PantryService;
use App\Services\AuditLogService;

$data = Request::json();
$item = PantryService::create($dbCfg, $userId, $data);

AuditLogService::log($dbCfg, $userId, 'create', 'pantry_items', $item['id'] ?? null, [
  'payload' => $data,
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok(['item' => $item], 201);
