<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\ProjectsService;

$rows = ProjectsService::list($dbCfg, $userId);
Response::ok(['projects' => $rows]);
