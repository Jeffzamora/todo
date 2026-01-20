<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TaskRemindersService;
use App\Services\AuditLogService;

try {
  TaskRemindersService::delete($dbCfg, $userId, $taskId, $reminderId);
  AuditLogService::log($dbCfg, $userId, 'delete_reminder', 'tasks', $taskId, ['reminder_id' => $reminderId]);
  Response::ok(['message' => 'Recordatorio eliminado']);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
