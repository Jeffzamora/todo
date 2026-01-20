<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class TasksService
{
  public static function list(array $dbCfg, int $userId, array $filters): array
  {
    $where = ["t.user_id = ?", "t.deleted_at IS NULL"];
    $params = [$userId];

    if (!empty($filters['project_id'])) {
      $where[] = "t.project_id = ?";
      $params[] = (int)$filters['project_id'];
    }

    if (!empty($filters['estado'])) {
      $where[] = "t.estado = ?";
      $params[] = (string)$filters['estado'];
    }

    if (!empty($filters['q'])) {
      $where[] = "(t.titulo LIKE ? OR t.descripcion LIKE ?)";
      $q = '%' . $filters['q'] . '%';
      $params[] = $q;
      $params[] = $q;
    }

    // due filter
    if (!empty($filters['due'])) {
      $due = (string)$filters['due'];
      if ($due === 'overdue') {
        $where[] = "t.due_at IS NOT NULL AND t.due_at < NOW() AND t.estado <> 'done'";
      } elseif ($due === 'today') {
        $where[] = "t.due_at IS NOT NULL AND DATE(t.due_at) = CURDATE()";
      } elseif ($due === 'week') {
        $where[] = "t.due_at IS NOT NULL AND t.due_at >= CURDATE() AND t.due_at < DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
      }
    }

    $sql = "
      SELECT
        t.id, t.project_id, t.parent_id,
        t.titulo, t.descripcion,
        t.prioridad, t.estado,
        t.start_at, t.due_at, t.done_at,
        t.is_star, t.orden,
        t.created_at, t.updated_at
      FROM tasks t
      WHERE " . implode(" AND ", $where) . "
      ORDER BY
        (t.estado='done') ASC,
        (t.due_at IS NULL) ASC,
        t.due_at ASC,
        t.orden ASC,
        t.id DESC
    ";

    return DB::fetchAll($sql, $params, $dbCfg);
  }

  public static function get(array $dbCfg, int $userId, int $taskId): array
  {
    $row = DB::fetchOne(
      "SELECT id, user_id, project_id, parent_id, titulo, descripcion, prioridad, estado,
              start_at, due_at, done_at, is_star, orden, created_at, updated_at
       FROM tasks
       WHERE id = ? AND user_id = ? AND deleted_at IS NULL
       LIMIT 1",
      [$taskId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Tarea no encontrada');
    return $row;
  }

  public static function create(array $dbCfg, int $userId, array $data): array
  {
    $titulo = trim((string)($data['titulo'] ?? ''));
    if ($titulo === '') throw new \RuntimeException('titulo es requerido');

    $projectId = isset($data['project_id']) ? (int)$data['project_id'] : null;
    $parentId  = isset($data['parent_id']) ? (int)$data['parent_id'] : null;

    $descripcion = $data['descripcion'] ?? null;
    $prioridad = $data['prioridad'] ?? 'med';
    $estado = $data['estado'] ?? 'todo';

    $startAt = $data['start_at'] ?? null;
    $dueAt   = $data['due_at'] ?? null;

    $isStar = (int)($data['is_star'] ?? 0);
    $orden  = (int)($data['orden'] ?? 0);

    $id = DB::insertGetId(
      "INSERT INTO tasks
        (user_id, project_id, parent_id, titulo, descripcion, prioridad, estado, start_at, due_at, is_star, orden)
       VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
      [$userId, $projectId, $parentId, $titulo, $descripcion, $prioridad, $estado, $startAt, $dueAt, $isStar, $orden],
      $dbCfg
    );

    return self::get($dbCfg, $userId, $id);
  }

public static function update(array $dbCfg, int $userId, int $taskId, array $data): array
{
  self::get($dbCfg, $userId, $taskId);

  $allowed = [
    'titulo','descripcion','project_id','parent_id','prioridad','estado',
    'start_at','due_at','is_star','orden'
  ];

  $set = [];
  $params = [];

  foreach ($allowed as $k) {
    if (array_key_exists($k, $data)) {
      $set[] = "$k = ?";
      $params[] = $data[$k]; // puede ser NULL ✅
    }
  }

  if (empty($set)) return self::get($dbCfg, $userId, $taskId);

  $set[] = "updated_at = NOW()";
  $params[] = $taskId;
  $params[] = $userId;

  DB::exec(
    "UPDATE tasks SET " . implode(', ', $set) . " WHERE id = ? AND user_id = ? AND deleted_at IS NULL",
    $params,
    $dbCfg
  );

  return self::get($dbCfg, $userId, $taskId);
}


  public static function setStatus(array $dbCfg, int $userId, int $taskId, string $status): array
  {
    $allowed = ['todo','doing','done','archived'];
    if (!in_array($status, $allowed, true)) throw new \RuntimeException('Estado inválido');

    // si cambia a done, set done_at
    if ($status === 'done') {
      DB::exec(
        "UPDATE tasks SET estado='done', done_at=NOW(), updated_at=NOW()
         WHERE id=? AND user_id=? AND deleted_at IS NULL",
        [$taskId, $userId],
        $dbCfg
      );
    } else {
      DB::exec(
        "UPDATE tasks SET estado=?, done_at=NULL, updated_at=NOW()
         WHERE id=? AND user_id=? AND deleted_at IS NULL",
        [$status, $taskId, $userId],
        $dbCfg
      );
    }

    return self::get($dbCfg, $userId, $taskId);
  }

  public static function softDelete(array $dbCfg, int $userId, int $taskId): void
  {
    DB::exec(
      "UPDATE tasks SET deleted_at=NOW(), updated_at=NOW()
       WHERE id=? AND user_id=? AND deleted_at IS NULL",
      [$taskId, $userId],
      $dbCfg
    );
  }
}
