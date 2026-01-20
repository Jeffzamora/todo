<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class TaskRemindersService
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

  private static function ensureReminderOwned(array $dbCfg, int $userId, int $taskId, int $reminderId): void
  {
    $row = DB::fetchOne(
      "SELECT r.id
       FROM task_reminders r
       INNER JOIN tasks t ON t.id = r.task_id
       WHERE r.id=? AND r.task_id=? AND t.user_id=? AND t.deleted_at IS NULL
       LIMIT 1",
      [$reminderId, $taskId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Recordatorio no encontrado');
  }

  public static function listForTask(array $dbCfg, int $userId, int $taskId): array
  {
    self::ensureTaskOwned($dbCfg, $userId, $taskId);

    return DB::fetchAll(
      "SELECT id, task_id, remind_at, channel, sent_at, created_at
       FROM task_reminders
       WHERE task_id = ?
       ORDER BY remind_at ASC, id ASC",
      [$taskId],
      $dbCfg
    );
  }

  public static function create(array $dbCfg, int $userId, int $taskId, array $data): array
  {
    self::ensureTaskOwned($dbCfg, $userId, $taskId);

    $remindAt = $data['remind_at'] ?? null;
    if (!$remindAt) throw new \RuntimeException('remind_at es requerido (YYYY-MM-DD HH:MM:SS)');

    $channel = (string)($data['channel'] ?? 'mobile_local'); // mobile_local|push|email
    if (!in_array($channel, ['mobile_local','push','email'], true)) {
      throw new \RuntimeException('channel inválido (mobile_local|push|email)');
    }

    $id = DB::insertGetId(
      "INSERT INTO task_reminders (task_id, remind_at, channel) VALUES (?, ?, ?)",
      [$taskId, $remindAt, $channel],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, task_id, remind_at, channel, sent_at, created_at
       FROM task_reminders
       WHERE id=? AND task_id=? LIMIT 1",
      [$id, $taskId],
      $dbCfg
    ) ?? [];
  }

  // UPDATE dinámico (permite NULL en sent_at si lo quieres limpiar)
  public static function update(array $dbCfg, int $userId, int $taskId, int $reminderId, array $data): array
  {
    self::ensureReminderOwned($dbCfg, $userId, $taskId, $reminderId);

    $allowed = ['remind_at', 'channel', 'sent_at'];
    $set = [];
    $params = [];

    foreach ($allowed as $k) {
      if (array_key_exists($k, $data)) {
        if ($k === 'channel' && $data[$k] !== null) {
          $ch = (string)$data[$k];
          if (!in_array($ch, ['mobile_local','push','email'], true)) {
            throw new \RuntimeException('channel inválido (mobile_local|push|email)');
          }
        }
        $set[] = "$k = ?";
        $params[] = $data[$k];
      }
    }

    if (empty($set)) {
      return DB::fetchOne(
        "SELECT id, task_id, remind_at, channel, sent_at, created_at
         FROM task_reminders
         WHERE id=? AND task_id=? LIMIT 1",
        [$reminderId, $taskId],
        $dbCfg
      ) ?? [];
    }

    $params[] = $reminderId;
    $params[] = $taskId;

    DB::exec(
      "UPDATE task_reminders SET " . implode(', ', $set) . " WHERE id=? AND task_id=?",
      $params,
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, task_id, remind_at, channel, sent_at, created_at
       FROM task_reminders
       WHERE id=? AND task_id=? LIMIT 1",
      [$reminderId, $taskId],
      $dbCfg
    ) ?? [];
  }

  public static function delete(array $dbCfg, int $userId, int $taskId, int $reminderId): void
  {
    self::ensureReminderOwned($dbCfg, $userId, $taskId, $reminderId);

    DB::exec(
      "DELETE FROM task_reminders WHERE id=? AND task_id=?",
      [$reminderId, $taskId],
      $dbCfg
    );
  }
}
