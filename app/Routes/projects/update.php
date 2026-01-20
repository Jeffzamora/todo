<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\ProjectsService;
use App\Services\AuditLogService;

$data = Request::json();

try {
  $row = ProjectsService::update($dbCfg, $userId, $projectId, $data);
  AuditLogService::log($dbCfg, $userId, 'update', 'projects', $projectId, ['changed'=>array_keys($data),'path'=>Request::path(),'method'=>Request::method()]);
  Response::ok(['project' => $row]);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Error actualizando proyecto', 422);
}