<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TaskRemindersService;

try {
  $reminders = TaskRemindersService::listForTask($dbCfg, $userId, $taskId);
  Response::ok(['reminders' => $reminders]);
} catch (Throwable $e) {
  Response::error($e->getMessage(), 422);
}
