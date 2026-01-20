<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\ShoppingListsService;
use App\Services\AuditLogService;

$data = Request::input();

try {
  $list = ShoppingListsService::create($dbCfg, $userId, $data);
  AuditLogService::log($dbCfg, $userId, 'create', 'shopping_lists', $list['id'] ?? null, ['nombre' => $list['nombre'] ?? null]);
  Response::ok(['list' => $list], 201);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
