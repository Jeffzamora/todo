<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\PantryService;
use App\Services\AuditLogService;

$data = Request::json();
$delta = (float)($data['delta'] ?? 0);

$item = PantryService::adjust($dbCfg, $userId, $pantryId, $delta);

AuditLogService::log($dbCfg, $userId, 'adjust', 'pantry_items', $pantryId, [
  'delta' => $delta,
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok(['item' => $item]);
