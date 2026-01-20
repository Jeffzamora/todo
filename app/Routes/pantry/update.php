<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\PantryService;
use App\Services\AuditLogService;

$data = Request::json();
$item = PantryService::update($dbCfg, $userId, $pantryId, $data);

AuditLogService::log($dbCfg, $userId, 'update', 'pantry_items', $pantryId, [
  'changed' => array_keys($data),
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok(['item' => $item]);
