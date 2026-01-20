<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TaskRemindersService;
use App\Services\AuditLogService;

$data = Request::input();

try {
  $reminder = TaskRemindersService::create($dbCfg, $userId, $taskId, $data);
  AuditLogService::log($dbCfg, $userId, 'create_reminder', 'tasks', $taskId, ['reminder_id' => $reminder['id'] ?? null, 'remind_at' => $reminder['remind_at'] ?? null]);
  Response::ok(['reminder' => $reminder], 201);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
