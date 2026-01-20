<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\ShoppingItemsService;
use App\Services\AuditLogService;

$data = Request::input();
$checked = (int)($data['is_checked'] ?? ($data['checked'] ?? 1));

try {
  $item = ShoppingItemsService::toggleChecked($dbCfg, $userId, $listId, $itemId, $checked ? 1 : 0);
  AuditLogService::log($dbCfg, $userId, 'check', 'shopping_items', $itemId, ['list_id' => $listId, 'is_checked' => $checked ? 1 : 0]);
  Response::ok(['item' => $item]);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
