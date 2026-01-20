<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TasksService;
use App\Services\AuditLogService;

try {
  TasksService::softDelete($dbCfg, $userId, $taskId);
  AuditLogService::log($dbCfg, $userId, 'delete', 'tasks', $taskId);
  Response::ok(['message' => 'Tarea eliminada']);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
