<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\PantryService;
use App\Services\AuditLogService;

PantryService::delete($dbCfg, $userId, $pantryId);

AuditLogService::log($dbCfg, $userId, 'delete', 'pantry_items', $pantryId, [
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok(['message' => 'deleted']);
