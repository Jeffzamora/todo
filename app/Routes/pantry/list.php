<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\PantryService;

Response::ok(['items' => PantryService::list($dbCfg, $userId)]);
