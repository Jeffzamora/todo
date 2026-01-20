<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\AuditLogService;

$filters = [
  'entity' => Request::query('entity'),
  'action' => Request::query('action'),
  'from'   => Request::query('from'),
  'to'     => Request::query('to'),
  'limit'  => Request::query('limit'),
];

Response::ok(['audit' => AuditLogService::list($dbCfg, $userId, $filters)]);
