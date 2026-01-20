<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TaskRecurringService;
use App\Services\AuditLogService;

try {
  TaskRecurringService::delete($dbCfg, $userId, $recId);
  AuditLogService::log($dbCfg, $userId, 'delete', 'task_recurring', $recId, ['path'=>Request::path(),'method'=>Request::method()]);
  Response::ok(['message' => 'deleted']);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Error eliminando recurrencia', 422);
}