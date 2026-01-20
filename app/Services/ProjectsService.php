<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class ProjectsService
{
  public static function list(array $dbCfg, int $userId): array
  {
    return DB::fetchAll(
      "SELECT id, nombre, color, icono, orden, estado, created_at, updated_at
       FROM projects
       WHERE user_id = ?
       ORDER BY orden ASC, id DESC",
      [$userId],
      $dbCfg
    );
  }

  public static function create(array $dbCfg, int $userId, array $data): array
  {
    $nombre = trim((string)($data['nombre'] ?? ''));
    if ($nombre === '') throw new \RuntimeException('nombre es requerido');

    $color = $data['color'] ?? null;
    $icono = $data['icono'] ?? null;
    $orden = (int)($data['orden'] ?? 0);

    $id = DB::insertGetId(
      "INSERT INTO projects (user_id, nombre, color, icono, orden, estado)
       VALUES (?, ?, ?, ?, ?, 'activo')",
      [$userId, $nombre, $color, $icono, $orden],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, nombre, color, icono, orden, estado, created_at, updated_at
       FROM projects WHERE id = ? AND user_id = ? LIMIT 1",
      [$id, $userId],
      $dbCfg
    ) ?? [];
  }

  public static function update(array $dbCfg, int $userId, int $projectId, array $data): array
  {
    $row = DB::fetchOne(
      "SELECT id FROM projects WHERE id = ? AND user_id = ? LIMIT 1",
      [$projectId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Proyecto no encontrado');

    $nombre = isset($data['nombre']) ? trim((string)$data['nombre']) : null;
    $color  = $data['color'] ?? null;
    $icono  = $data['icono'] ?? null;
    $orden  = isset($data['orden']) ? (int)$data['orden'] : null;
    $estado = isset($data['estado']) ? (string)$data['estado'] : null;

    // update parcial
    DB::exec(
      "UPDATE projects SET
        nombre = COALESCE(?, nombre),
        color  = COALESCE(?, color),
        icono  = COALESCE(?, icono),
        orden  = COALESCE(?, orden),
        estado = COALESCE(?, estado),
        updated_at = NOW()
       WHERE id = ? AND user_id = ?",
      [$nombre, $color, $icono, $orden, $estado, $projectId, $userId],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, nombre, color, icono, orden, estado, created_at, updated_at
       FROM projects WHERE id = ? AND user_id = ? LIMIT 1",
      [$projectId, $userId],
      $dbCfg
    ) ?? [];
  }

  public static function archive(array $dbCfg, int $userId, int $projectId): void
  {
    DB::exec(
      "UPDATE projects SET estado='archivado', updated_at=NOW()
       WHERE id = ? AND user_id = ?",
      [$projectId, $userId],
      $dbCfg
    );
  }
}
