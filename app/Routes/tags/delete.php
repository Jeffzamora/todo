<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TagsService;
use App\Services\AuditLogService;

TagsService::delete($dbCfg, $userId, $tagId);
AuditLogService::log($dbCfg, $userId, 'delete', 'tags', $tagId, ['path'=>Request::path(),'method'=>Request::method()]);
  Response::ok(['message' => 'deleted']);