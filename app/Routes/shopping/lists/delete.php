<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\ShoppingListsService;
use App\Services\AuditLogService;

try {
  ShoppingListsService::delete($dbCfg, $userId, $listId);
  AuditLogService::log($dbCfg, $userId, 'delete', 'shopping_lists', $listId, []);
  Response::ok(['message' => 'Lista eliminada']);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
