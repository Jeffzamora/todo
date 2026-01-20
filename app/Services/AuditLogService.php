<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class AuditLogService
{
  public static function log(
    array $dbCfg,
    ?int $userId,           // ✅ ahora permite NULL
    string $action,
    string $entity,
    $entityId = null,
    array $meta = []
  ): void {

    $ip = self::ip();
    $ua = self::userAgent();

    $metaJson = null;
    if (!empty($meta)) {
      $encoded = json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      if ($encoded !== false) $metaJson = $encoded;
    }

    DB::exec(
      "INSERT INTO audit_log (user_id, action, entity, entity_id, ip, user_agent, meta, created_at)
       VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
      [
        $userId, // ✅ puede ser NULL
        $action,
        $entity,
        $entityId !== null ? (string)$entityId : null,
        $ip,
        $ua,
        $metaJson
      ],
      $dbCfg
    );
  }

  public static function list(array $dbCfg, int $userId, array $filters = []): array
  {
    $where = ["user_id = ?"];
    $params = [$userId];

    if (!empty($filters['entity'])) { $where[] = "entity = ?"; $params[] = (string)$filters['entity']; }
    if (!empty($filters['action'])) { $where[] = "action = ?"; $params[] = (string)$filters['action']; }
    if (!empty($filters['from']))   { $where[] = "created_at >= ?"; $params[] = (string)$filters['from']; }
    if (!empty($filters['to']))     { $where[] = "created_at <= ?"; $params[] = (string)$filters['to']; }

    $limit = (int)($filters['limit'] ?? 50);
    if ($limit < 1) $limit = 50;
    if ($limit > 200) $limit = 200;

    return DB::fetchAll(
      "SELECT id, user_id, action, entity, entity_id, ip, user_agent, meta, created_at
       FROM audit_log
       WHERE " . implode(" AND ", $where) . "
       ORDER BY id DESC
       LIMIT " . $limit,
      $params,
      $dbCfg
    );
  }

  private static function ip(): ?string { return $_SERVER['REMOTE_ADDR'] ?? null; }
  private static function userAgent(): ?string {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;
    if ($ua !== null && strlen($ua) > 255) $ua = substr($ua, 0, 255);
    return $ua;
  }
}
