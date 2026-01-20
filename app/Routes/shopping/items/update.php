<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\ShoppingItemsService;
use App\Services\AuditLogService;

$data = Request::input();

try {
  $item = ShoppingItemsService::update($dbCfg, $userId, $listId, $itemId, $data);
  AuditLogService::log($dbCfg, $userId, 'update', 'shopping_items', $itemId, ['list_id' => $listId, 'changed' => array_keys($data)]);
  Response::ok(['item' => $item]);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
