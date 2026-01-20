<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Utils\Request;
use App\Services\TaskRecurringService;
use App\Config\Config;

$key = (string)Request::query('key');
$cronKey = (string)Config::env('CRON_KEY', '');

if ($cronKey === '' || $key !== $cronKey) {
  Response::error('Unauthorized', 401);
}

$tz = Config::env('APP_TZ', 'America/Los_Angeles');
$created = TaskRecurringService::runDue($dbCfg, $tz, 50);

Response::ok(['created' => $created]);
