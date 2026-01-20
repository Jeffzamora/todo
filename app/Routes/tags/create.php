<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TagsService;
use App\Services\AuditLogService;

$data = Request::json();
try {
  $tag = TagsService::create($dbCfg, $userId, $data);
  Response::ok(['tag' => $tag], 201);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Error creando tag', 422);
}