<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\ShoppingListsService;
use App\Services\AuditLogService;

$data = Request::input();

try {
  $list = ShoppingListsService::update($dbCfg, $userId, $listId, $data);
  AuditLogService::log($dbCfg, $userId, 'update', 'shopping_lists', $listId, ['changed' => array_keys($data)]);
  Response::ok(['list' => $list]);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
