<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class TaskTagsService
{
  private static function ensureTaskOwned(array $dbCfg, int $userId, int $taskId): void
  {
    $row = DB::fetchOne(
      "SELECT id FROM tasks WHERE id=? AND user_id=? AND deleted_at IS NULL LIMIT 1",
      [$taskId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Tarea no encontrada');
  }

  private static function ensureTagOwned(array $dbCfg, int $userId, int $tagId): void
  {
    $row = DB::fetchOne(
      "SELECT id FROM tags WHERE id=? AND user_id=? LIMIT 1",
      [$tagId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Tag no encontrado');
  }

  public static function listForTask(array $dbCfg, int $userId, int $taskId): array
  {
    self::ensureTaskOwned($dbCfg, $userId, $taskId);

    return DB::fetchAll(
      "SELECT tg.id, tg.nombre, tg.color
       FROM task_tags tt
       INNER JOIN tags tg ON tg.id = tt.tag_id
       WHERE tt.task_id = ? AND tg.user_id = ?
       ORDER BY tg.nombre ASC",
      [$taskId, $userId],
      $dbCfg
    );
  }

  public static function add(array $dbCfg, int $userId, int $taskId, int $tagId): void
  {
    self::ensureTaskOwned($dbCfg, $userId, $taskId);
    self::ensureTagOwned($dbCfg, $userId, $tagId);

    DB::exec(
      "INSERT IGNORE INTO task_tags (task_id, tag_id) VALUES (?, ?)",
      [$taskId, $tagId],
      $dbCfg
    );
  }

  public static function remove(array $dbCfg, int $userId, int $taskId, int $tagId): void
  {
    self::ensureTaskOwned($dbCfg, $userId, $taskId);
    self::ensureTagOwned($dbCfg, $userId, $tagId);

    DB::exec(
      "DELETE FROM task_tags WHERE task_id=? AND tag_id=?",
      [$taskId, $tagId],
      $dbCfg
    );
  }
}
