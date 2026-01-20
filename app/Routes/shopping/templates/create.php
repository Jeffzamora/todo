<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\ShoppingTemplatesService;
use App\Services\AuditLogService;

$data = Request::json();
$t = ShoppingTemplatesService::create($dbCfg, $userId, $data);

AuditLogService::log($dbCfg, $userId, 'create', 'shopping_templates', $t['id'] ?? null, [
  'payload' => $data,
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok(['template' => $t], 201);
