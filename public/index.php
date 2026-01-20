<?php
declare(strict_types=1);

use App\Config\DotEnv;
use App\Config\Config;
use App\Database\DB;
use App\Utils\Response;
use App\Utils\Request;

/* ===============================
   BOOTSTRAP (SOLO CLASES)
   =============================== */
require __DIR__ . '/../app/Config/DotEnv.php';
require __DIR__ . '/../app/Config/Config.php';
require __DIR__ . '/../app/Database/DB.php';

require __DIR__ . '/../app/Utils/Response.php';
require __DIR__ . '/../app/Utils/Request.php';

require __DIR__ . '/../app/Security/Jwt.php';
require __DIR__ . '/../app/Middleware/AuthMiddleware.php';

require __DIR__ . '/../app/Services/AuthService.php';
require __DIR__ . '/../app/Services/ProjectsService.php';
require __DIR__ . '/../app/Services/TasksService.php';
require __DIR__ . '/../app/Services/ShoppingListsService.php';
require __DIR__ . '/../app/Services/ShoppingItemsService.php';
require __DIR__ . '/../app/Services/TagsService.php';
require __DIR__ . '/../app/Services/TaskTagsService.php';
require __DIR__ . '/../app/Services/TaskRemindersService.php';
require __DIR__ . '/../app/Services/TaskRecurringService.php';

require __DIR__ . '/../app/Services/AuditLogService.php';

require __DIR__ . '/../app/Services/PantryService.php';
require __DIR__ . '/../app/Services/ShoppingTemplatesService.php';
require __DIR__ . '/../app/Services/ShoppingTemplateItemsService.php';

require __DIR__ . '/../app/Services/UsersService.php';






/* ===============================
   ENV + CONFIG
   =============================== */
DotEnv::load(__DIR__ . '/../.env');

$dbCfg = Config::db();
$debug = Config::bool('APP_DEBUG', false);

/* ===============================
   JWT CONFIG
   =============================== */
$jwtCfg = [
  'iss' => Config::env('JWT_ISSUER', 'todo_pro_api'),
  'aud' => Config::env('JWT_AUD', 'todo_pro_app'),
  'secret' => Config::env('JWT_SECRET', ''),
  'ttl_min' => Config::int('JWT_TTL_MIN', 15),
];

$refreshCfg = [
  'ttl_days' => Config::int('REFRESH_TTL_DAYS', 30),
  'secret'   => Config::env('REFRESH_SECRET', ''),
];

if (!$jwtCfg['secret']) throw new RuntimeException('JWT_SECRET no configurado en .env');
if (!$refreshCfg['secret']) throw new RuntimeException('REFRESH_SECRET no configurado en .env');


/* ===============================
   CORS
   =============================== */
header('Access-Control-Allow-Origin: https://devzamora.com');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

if (Request::method() === 'OPTIONS') {
  http_response_code(204);
  exit;
}

/* ===============================
   ROUTER
   =============================== */
try {
  $method = Request::method();
  $path   = Request::path();

  // base path auto (ej: /todo/public)
  $script = $_SERVER['SCRIPT_NAME'] ?? '';
  $base = rtrim(str_replace('/index.php', '', $script), '/');
  if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
    $path = rtrim($path, '/') ?: '/';
  }

  /* ===============================
     PUBLIC ROUTES
     =============================== */
  if ($method === 'GET' && $path === '/') {
    Response::ok([
      'app' => Config::env('APP_NAME', 'todo_pro'),
      'env' => Config::env('APP_ENV', 'local'),
    ]);
  }

  if ($method === 'GET' && $path === '/health') {
    DB::fetchOne('SELECT 1', [], $dbCfg);
    Response::ok(['status' => 'healthy']);
  }

  /* ===============================
     AUTH (NO PROTEGIDO)
     =============================== */
  if ($method === 'POST' && $path === '/auth/login') {
    require __DIR__ . '/../app/Routes/auth/login.php';
  }
  if ($method === 'POST' && $path === '/auth/refresh') {
    require __DIR__ . '/../app/Routes/auth/refresh.php';
  }
  if ($method === 'POST' && $path === '/auth/logout') {
    require __DIR__ . '/../app/Routes/auth/logout.php';
  }

  /* ===============================
     PROTECTED ROUTES
     =============================== */
  $payload = null;
  $userId = 0;

  if (
    str_starts_with($path, '/projects') ||
    str_starts_with($path, '/tasks') ||
    str_starts_with($path, '/shopping') ||
    str_starts_with($path, '/tags') ||
    str_starts_with($path, '/recurring') ||
    str_starts_with($path, '/audit') ||
    str_starts_with($path, '/pantry') ||
    str_starts_with($path, '/shopping/templates') ||
    str_starts_with($path, '/users')
  ) {
    $payload = \App\Middleware\AuthMiddleware::requireAuth($jwtCfg);
    $userId = (int)($payload['sub'] ?? 0);
    if ($userId <= 0) Response::error('Token inválido', 401);
  }

  /* ===============================
     USERS / PROFILE
     =============================== */
  if ($method === 'GET' && $path === '/users/me') {
    require __DIR__ . '/../app/Routes/users/me_get.php';
  }
  if (($method === 'PUT' || $method === 'PATCH') && $path === '/users/me') {
    require __DIR__ . '/../app/Routes/users/me_update.php';
  }
  if (($method === 'PUT' || $method === 'PATCH') && $path === '/users/me/password') {
    require __DIR__ . '/../app/Routes/users/password_update.php';
  }

  /* ===============================
     PROJECTS
     =============================== */
  if ($method === 'GET' && $path === '/projects') {
    require __DIR__ . '/../app/Routes/projects/list.php';
  }
  if ($method === 'POST' && $path === '/projects') {
    require __DIR__ . '/../app/Routes/projects/create.php';
  }
  if (preg_match('#^/projects/(\d+)$#', $path, $m)) {
    $projectId = (int)$m[1];
    if ($method === 'PUT' || $method === 'PATCH') require __DIR__ . '/../app/Routes/projects/update.php';
    if ($method === 'DELETE') require __DIR__ . '/../app/Routes/projects/delete.php';
  }

  // Proyectos -> tareas (endpoint helper para evitar errores con query params)
  if (preg_match('#^/projects/(\d+)/tasks$#', $path, $m)) {
    $projectId = (int)$m[1];
    if ($method === 'GET') require __DIR__ . '/../app/Routes/projects/tasks.php';
  }

  /* ===============================
     TASKS
     =============================== */
  if ($method === 'GET' && $path === '/tasks') {
    require __DIR__ . '/../app/Routes/tasks/list.php';
  }
  if ($method === 'POST' && $path === '/tasks') {
    require __DIR__ . '/../app/Routes/tasks/create.php';
  }
  if (preg_match('#^/tasks/(\d+)$#', $path, $m)) {
    $taskId = (int)$m[1];
    if ($method === 'GET') require __DIR__ . '/../app/Routes/tasks/get.php';
    if ($method === 'PUT' || $method === 'PATCH') require __DIR__ . '/../app/Routes/tasks/update.php';
    if ($method === 'DELETE') require __DIR__ . '/../app/Routes/tasks/delete.php';
  }
  if (preg_match('#^/tasks/(\d+)/status$#', $path, $m)) {
    $taskId = (int)$m[1];
    if ($method === 'PATCH') require __DIR__ . '/../app/Routes/tasks/status.php';
  }

  /* TAGS */
if ($method === 'GET' && $path === '/tags') {
  require __DIR__ . '/../app/Routes/tags/list.php';
}
if ($method === 'POST' && $path === '/tags') {
  require __DIR__ . '/../app/Routes/tags/create.php';
}
if (preg_match('#^/tags/(\d+)$#', $path, $m)) {
  $tagId = (int)$m[1];
  if ($method === 'DELETE') require __DIR__ . '/../app/Routes/tags/delete.php';
}

/* TASK TAGS */
if (preg_match('#^/tasks/(\d+)/tags$#', $path, $m)) {
  $taskId = (int)$m[1];
  if ($method === 'GET') require __DIR__ . '/../app/Routes/tasks/tags_list.php';
  if ($method === 'POST') require __DIR__ . '/../app/Routes/tasks/tags_add.php';
}
if (preg_match('#^/tasks/(\d+)/tags/(\d+)$#', $path, $m)) {
  $taskId = (int)$m[1];
  $tagId = (int)$m[2];
  if ($method === 'DELETE') require __DIR__ . '/../app/Routes/tasks/tags_remove.php';
}

// TASK REMINDERS
if (preg_match('#^/tasks/(\d+)/reminders$#', $path, $m)) {
  $taskId = (int)$m[1];
  if ($method === 'GET')  require __DIR__ . '/../app/Routes/tasks/reminders/list.php';
  if ($method === 'POST') require __DIR__ . '/../app/Routes/tasks/reminders/create.php';
}

if (preg_match('#^/tasks/(\d+)/reminders/(\d+)$#', $path, $m)) {
  $taskId = (int)$m[1];
  $reminderId = (int)$m[2];
  if ($method === 'PUT' || $method === 'PATCH') require __DIR__ . '/../app/Routes/tasks/reminders/update.php';
  if ($method === 'DELETE') require __DIR__ . '/../app/Routes/tasks/reminders/delete.php';
}

  /* ===============================
     AUDIT LOGS
     =============================== */

  if ($method === 'GET' && $path === '/audit') {
  require __DIR__ . '/../app/Routes/audit/list.php';
}


  /* ===============================
     SHOPPING LISTS
     =============================== */
  if ($method === 'GET' && $path === '/shopping/lists') {
    require __DIR__ . '/../app/Routes/shopping/lists/list.php';
  }
  if ($method === 'POST' && $path === '/shopping/lists') {
    require __DIR__ . '/../app/Routes/shopping/lists/create.php';
  }
  if (preg_match('#^/shopping/lists/(\d+)$#', $path, $m)) {
    $listId = (int)$m[1];
    if ($method === 'PUT' || $method === 'PATCH') require __DIR__ . '/../app/Routes/shopping/lists/update.php';
    if ($method === 'DELETE') require __DIR__ . '/../app/Routes/shopping/lists/delete.php';
  }

    /* ===============================
     RECURRING
     =============================== */

    if ($method === 'GET' && $path === '/recurring') {
      require __DIR__ . '/../app/Routes/recurring/list.php';
    }
      if ($method === 'POST' && $path === '/recurring') {
      require __DIR__ . '/../app/Routes/recurring/create.php';
    }
      if (preg_match('#^/recurring/(\d+)$#', $path, $m)) {
        $recId = (int)$m[1];
        if ($method === 'PUT' || $method === 'PATCH') require __DIR__ . '/../app/Routes/recurring/update.php';
        if ($method === 'DELETE') require __DIR__ . '/../app/Routes/recurring/delete.php';
    }

    if ($method === 'POST' && $path === '/cron/recurring/run') {
      require __DIR__ . '/../app/Routes/cron/recurring_run.php';
    }



  /* ===============================
     SHOPPING ITEMS
     =============================== */
  if (preg_match('#^/shopping/lists/(\d+)/items$#', $path, $m)) {
    $listId = (int)$m[1];
    if ($method === 'GET') require __DIR__ . '/../app/Routes/shopping/items/list.php';
    if ($method === 'POST') require __DIR__ . '/../app/Routes/shopping/items/create.php';
  }

  if (preg_match('#^/shopping/lists/(\d+)/items/(\d+)$#', $path, $m)) {
    $listId = (int)$m[1];
    $itemId = (int)$m[2];
    if ($method === 'PUT' || $method === 'PATCH') require __DIR__ . '/../app/Routes/shopping/items/update.php';
    if ($method === 'DELETE') require __DIR__ . '/../app/Routes/shopping/items/delete.php';
  }

  if (preg_match('#^/shopping/lists/(\d+)/items/(\d+)/check$#', $path, $m)) {
    $listId = (int)$m[1];
    $itemId = (int)$m[2];
    if ($method === 'PATCH') require __DIR__ . '/../app/Routes/shopping/items/check.php';
  }

  /* ===============================
   PANTRY
   =============================== */
if ($method === 'GET' && $path === '/pantry') {
  require __DIR__ . '/../app/Routes/pantry/list.php';
}
if ($method === 'POST' && $path === '/pantry') {
  require __DIR__ . '/../app/Routes/pantry/create.php';
}
if (preg_match('#^/pantry/(\\d+)$#', $path, $m)) {
  $pantryId = (int)$m[1];
  if ($method === 'PUT' || $method === 'PATCH') require __DIR__ . '/../app/Routes/pantry/update.php';
  if ($method === 'DELETE') require __DIR__ . '/../app/Routes/pantry/delete.php';
}
if (preg_match('#^/pantry/(\\d+)/adjust$#', $path, $m)) {
  $pantryId = (int)$m[1];
  if ($method === 'PATCH') require __DIR__ . '/../app/Routes/pantry/adjust.php';
}

/* ===============================
   SHOPPING TEMPLATES
   =============================== */
if ($method === 'GET' && $path === '/shopping/templates') {
  require __DIR__ . '/../app/Routes/shopping/templates/list.php';
}
if ($method === 'POST' && $path === '/shopping/templates') {
  require __DIR__ . '/../app/Routes/shopping/templates/create.php';
}
if (preg_match('#^/shopping/templates/(\\d+)$#', $path, $m)) {
  $templateId = (int)$m[1];
  if ($method === 'DELETE') require __DIR__ . '/../app/Routes/shopping/templates/delete.php';
}
if (preg_match('#^/shopping/templates/(\\d+)/create-list$#', $path, $m)) {
  $templateId = (int)$m[1];
  if ($method === 'POST') require __DIR__ . '/../app/Routes/shopping/templates/create_list.php';
}

/* TEMPLATE ITEMS */
if (preg_match('#^/shopping/templates/(\\d+)/items$#', $path, $m)) {
  $templateId = (int)$m[1];
  if ($method === 'GET') require __DIR__ . '/../app/Routes/shopping/template_items/list.php';
  if ($method === 'POST') require __DIR__ . '/../app/Routes/shopping/template_items/create.php';
}
if (preg_match('#^/shopping/templates/(\\d+)/items/(\\d+)$#', $path, $m)) {
  $templateId = (int)$m[1];
  $templateItemId = (int)$m[2];
  if ($method === 'PUT' || $method === 'PATCH') require __DIR__ . '/../app/Routes/shopping/template_items/update.php';
  if ($method === 'DELETE') require __DIR__ . '/../app/Routes/shopping/template_items/delete.php';
}



  Response::error('Not found', 404, ['path' => $path, 'method' => $method]);

} catch (\RuntimeException $e) {
  $code = (int)$e->getCode();
  if ($code < 400 || $code > 599) $code = 400;
  Response::error($debug ? $e->getMessage() : 'Error', $code);
} catch (Throwable $e) {
  Response::error($debug ? $e->getMessage() : 'Server error', 500);
}
