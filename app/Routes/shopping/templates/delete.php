<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\ShoppingTemplatesService;
use App\Services\AuditLogService;

ShoppingTemplatesService::delete($dbCfg, $userId, $templateId);

AuditLogService::log($dbCfg, $userId, 'delete', 'shopping_templates', $templateId, [
  'path' => Request::path(),
  'method' => Request::method()
]);

Response::ok(['message' => 'deleted']);
