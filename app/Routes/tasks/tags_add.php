<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TaskTagsService;
use App\Services\AuditLogService;

$data = Request::json();
$tagId = (int)($data['tag_id'] ?? 0);
if ($tagId <= 0) Response::error('tag_id es requerido', 422);

try {
  TaskTagsService::add($dbCfg, $userId, $taskId, $tagId);
  Response::ok(['message' => 'added']);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Error asignando tag', 422);
}