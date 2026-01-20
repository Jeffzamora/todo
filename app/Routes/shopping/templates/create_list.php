<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\ShoppingTemplatesService;
use App\Services\AuditLogService;

$payload = Request::json();
$res = ShoppingTemplatesService::createListFromTemplate($dbCfg, $userId, $templateId, $payload);

AuditLogService::log($dbCfg, $userId, 'create', 'shopping_lists', $res['list_id'] ?? null, [
  'from_template_id' => $templateId,
  'payload' => $payload,
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok($res, 201);
