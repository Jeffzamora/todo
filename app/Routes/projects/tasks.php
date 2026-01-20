<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TasksService;

// Lista tareas de un proyecto.
// Soporta los mismos filtros opcionales que /tasks: estado, q, due

$filters = [
  'project_id' => (string)($projectId ?? ''),
  'estado'     => Request::query('estado'),
  'q'          => Request::query('q'),
  'due'        => Request::query('due'),
];

$rows = TasksService::list($dbCfg, $userId, $filters);
Response::ok(['tasks' => $rows]);
