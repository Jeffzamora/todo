<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\ProjectsService;
use App\Services\AuditLogService;

$data = Request::json();
try {
  $row = ProjectsService::create($dbCfg, $userId, $data);
  AuditLogService::log($dbCfg, $userId, 'create', 'projects', $row['id'] ?? null, ['payload'=>$data,'path'=>Request::path(),'method'=>Request::method()]);
  Response::ok(['project' => $row], 201);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Error creando proyecto', 422);
}