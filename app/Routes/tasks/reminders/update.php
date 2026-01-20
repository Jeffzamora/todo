<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TaskRemindersService;
use App\Services\AuditLogService;

$data = Request::input();

try {
  $reminder = TaskRemindersService::update($dbCfg, $userId, $taskId, $reminderId, $data);
  AuditLogService::log($dbCfg, $userId, 'update_reminder', 'tasks', $taskId, ['reminder_id' => $reminderId, 'changed' => array_keys($data)]);
  Response::ok(['reminder' => $reminder]);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
