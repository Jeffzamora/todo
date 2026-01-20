<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TasksService;
use App\Services\AuditLogService;

$data = Request::input();

try {
  $task = TasksService::update($dbCfg, $userId, $taskId, $data);
  AuditLogService::log($dbCfg, $userId, 'update', 'tasks', $taskId, ['changed' => array_keys($data)]);
  Response::ok(['task' => $task]);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
