<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class TagsService
{
  public static function list(array $dbCfg, int $userId): array
  {
    return DB::fetchAll(
      "SELECT id, nombre, color, created_at
       FROM tags
       WHERE user_id = ?
       ORDER BY nombre ASC",
      [$userId],
      $dbCfg
    );
  }

  public static function create(array $dbCfg, int $userId, array $data): array
  {
    $nombre = trim((string)($data['nombre'] ?? ''));
    if ($nombre === '') throw new \RuntimeException('nombre es requerido');

    $color = $data['color'] ?? null;

    $id = DB::insertGetId(
      "INSERT INTO tags (user_id, nombre, color) VALUES (?, ?, ?)",
      [$userId, $nombre, $color],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, nombre, color, created_at FROM tags WHERE id=? AND user_id=? LIMIT 1",
      [$id, $userId],
      $dbCfg
    ) ?? [];
  }

  public static function delete(array $dbCfg, int $userId, int $tagId): void
  {
    // borra relaciones primero
    DB::exec(
      "DELETE tt FROM task_tags tt
       INNER JOIN tasks t ON t.id = tt.task_id
       WHERE tt.tag_id = ? AND t.user_id = ?",
      [$tagId, $userId],
      $dbCfg
    );

    DB::exec(
      "DELETE FROM tags WHERE id=? AND user_id=?",
      [$tagId, $userId],
      $dbCfg
    );
  }
}
