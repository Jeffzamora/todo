<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\ShoppingTemplateItemsService;
use App\Services\AuditLogService;

$data = Request::json();
$item = ShoppingTemplateItemsService::update($dbCfg, $userId, $templateId, $templateItemId, $data);

AuditLogService::log($dbCfg, $userId, 'update', 'shopping_template_items', $templateItemId, [
  'template_id' => $templateId,
  'changed' => array_keys($data),
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok(['item' => $item]);
