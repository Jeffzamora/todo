<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TaskRecurringService;
use App\Config\Config;
use App\Services\AuditLogService;

$data = Request::json();
$tz = Config::env('APP_TZ', 'America/Los_Angeles');

try {
  $row = TaskRecurringService::update($dbCfg, $userId, $recId, $data, $tz);
  AuditLogService::log($dbCfg, $userId, 'update', 'task_recurring', $recId, ['changed'=>array_keys($data),'path'=>Request::path(),'method'=>Request::method()]);
  Response::ok(['recurring' => $row]);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Error actualizando recurrencia', 422);
}