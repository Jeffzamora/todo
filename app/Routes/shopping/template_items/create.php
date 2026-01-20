<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\ShoppingTemplateItemsService;
use App\Services\AuditLogService;

$data = Request::json();
$item = ShoppingTemplateItemsService::create($dbCfg, $userId, $templateId, $data);

AuditLogService::log($dbCfg, $userId, 'create', 'shopping_template_items', $item['id'] ?? null, [
  'template_id' => $templateId,
  'payload' => $data,
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok(['item' => $item], 201);
