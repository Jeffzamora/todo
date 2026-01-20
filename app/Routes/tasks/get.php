<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TasksService;

try {
  $row = TasksService::get($dbCfg, $userId, $taskId);
  Response::ok(['task' => $row]);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Tarea no encontrada', 404);
}
