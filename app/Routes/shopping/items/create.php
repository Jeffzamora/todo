<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\ShoppingItemsService;
use App\Services\AuditLogService;

$data = Request::input();

try {
  $item = ShoppingItemsService::create($dbCfg, $userId, $listId, $data);
  AuditLogService::log($dbCfg, $userId, 'create', 'shopping_items', $item['id'] ?? null, ['list_id' => $listId, 'nombre' => $item['nombre'] ?? null]);
  Response::ok(['item' => $item], 201);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
