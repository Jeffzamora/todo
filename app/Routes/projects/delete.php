<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\ProjectsService;
use App\Services\AuditLogService;

ProjectsService::archive($dbCfg, $userId, $projectId);
Response::ok(['message' => 'archivado']);