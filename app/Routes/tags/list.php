<?php
declare(strict_types=1);

use App\Utils\Response;
use App\Services\TagsService;

Response::ok(['tags' => TagsService::list($dbCfg, $userId)]);
