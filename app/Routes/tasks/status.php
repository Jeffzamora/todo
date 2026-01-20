<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TasksService;
use App\Services\AuditLogService;

$data = Request::json();
$status = (string)($data['estado'] ?? '');

try {
  $row = TasksService::setStatus($dbCfg, $userId, $taskId, $status);
  AuditLogService::log($dbCfg, $userId, 'status', 'tasks', $taskId, ['status'=>$status,'path'=>Request::path(),'method'=>Request::method()]);
  Response::ok(['task' => $row]);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Error cambiando estado', 422);
}