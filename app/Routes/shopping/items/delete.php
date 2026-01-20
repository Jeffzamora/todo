<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\ShoppingItemsService;
use App\Services\AuditLogService;

try {
  ShoppingItemsService::delete($dbCfg, $userId, $listId, $itemId);
  AuditLogService::log($dbCfg, $userId, 'delete', 'shopping_items', $itemId, ['list_id' => $listId]);
  Response::ok(['message' => 'Item eliminado']);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
