<?php
declare(strict_types=1);

use App\Utils\Request;
use App\Utils\Response;
use App\Services\TasksService;

// Nota:
// Antes esta ruta atrapaba TODO y devolvía 422, lo cual escondía errores reales
// (401/500/SQL). Ahora dejamos que index.php maneje las excepciones.

$filters = [
  'project_id' => Request::query('project_id'),
  'estado'     => Request::query('estado'),
  'q'          => Request::query('q'),
  'due'        => Request::query('due'),
];

$rows = TasksService::list($dbCfg, $userId, $filters);
Response::ok(['tasks' => $rows]);
