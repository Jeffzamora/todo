<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\ShoppingTemplateItemsService;
use App\Services\AuditLogService;

ShoppingTemplateItemsService::delete($dbCfg, $userId, $templateId, $templateItemId);

AuditLogService::log($dbCfg, $userId, 'delete', 'shopping_template_items', $templateItemId, [
  'template_id' => $templateId,
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok(['message' => 'deleted']);
