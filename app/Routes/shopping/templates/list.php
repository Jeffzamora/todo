<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\ShoppingTemplatesService;

Response::ok(['templates' => ShoppingTemplatesService::list($dbCfg, $userId)]);
