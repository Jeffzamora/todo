<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TaskTagsService;
use App\Services\AuditLogService;

try {
  TaskTagsService::remove($dbCfg, $userId, $taskId, $tagId);
  Response::ok(['message' => 'removed']);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Error quitando tag', 422);
}