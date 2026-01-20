<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\ShoppingListsService;

Response::ok(['lists' => ShoppingListsService::list($dbCfg, $userId)]);
