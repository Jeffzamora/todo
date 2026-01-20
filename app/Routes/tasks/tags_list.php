<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TaskTagsService;

Response::ok(['tags' => TaskTagsService::listForTask($dbCfg, $userId, $taskId)]);
