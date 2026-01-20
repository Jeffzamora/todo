<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TaskRecurringService;

Response::ok(['recurring' => TaskRecurringService::list($dbCfg, $userId)]);
