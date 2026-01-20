<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\ShoppingTemplateItemsService;

Response::ok(['items' => ShoppingTemplateItemsService::list($dbCfg, $userId, $templateId)]);
