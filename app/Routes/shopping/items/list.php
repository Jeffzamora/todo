<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\ShoppingItemsService;

try {
  $items = ShoppingItemsService::list($dbCfg, $userId, $listId);
  Response::ok(['items' => $items]);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
