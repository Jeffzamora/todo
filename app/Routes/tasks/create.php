<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TasksService;
use App\Services\AuditLogService;

$data = Request::input();

try {
  $task = TasksService::create($dbCfg, $userId, $data);
  AuditLogService::log($dbCfg, $userId, 'create', 'tasks', $task['id'] ?? null, ['data_keys' => array_keys($data)]);
  Response::ok(['task' => $task], 201);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
