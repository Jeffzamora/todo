<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class TaskRecurringService
{
  /* =========================
     CRUD
     ========================= */

  public static function list(array $dbCfg, int $userId): array
  {
    return DB::fetchAll(
      "SELECT tr.id, tr.task_id, tr.rule_json, tr.next_run_at, tr.last_run_at, tr.is_active, tr.created_at, tr.updated_at,
              t.titulo AS template_titulo, t.project_id AS template_project_id
       FROM task_recurring tr
       INNER JOIN tasks t ON t.id = tr.task_id
       WHERE t.user_id = ? AND t.deleted_at IS NULL
       ORDER BY tr.is_active DESC, tr.next_run_at ASC, tr.id DESC",
      [$userId],
      $dbCfg
    );
  }

  public static function create(array $dbCfg, int $userId, array $data, string $defaultTz): array
  {
    $taskId = (int)($data['task_id'] ?? 0);
    if ($taskId <= 0) throw new \RuntimeException('task_id es requerido');

    self::ensureTaskOwned($dbCfg, $userId, $taskId);

    // rule puede venir como objeto o como string JSON
    $rule = $data['rule'] ?? $data['rule_json'] ?? null;
    $ruleArr = self::normalizeRule($rule, $defaultTz);

    $nextRunAt = self::computeFirstNextRunAt($ruleArr);

    $ruleJson = json_encode($ruleArr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($ruleJson === false) throw new \RuntimeException('rule inválida');

    $id = DB::insertGetId(
      "INSERT INTO task_recurring (task_id, rule_json, next_run_at, last_run_at, is_active)
       VALUES (?, ?, ?, NULL, 1)",
      [$taskId, $ruleJson, $nextRunAt],
      $dbCfg
    );

    return self::get($dbCfg, $userId, (int)$id);
  }

  public static function update(array $dbCfg, int $userId, int $recId, array $data, string $defaultTz): array
  {
    $row = self::getRowOwned($dbCfg, $userId, $recId);

    $set = [];
    $params = [];

    if (array_key_exists('is_active', $data)) {
      $set[] = "is_active = ?";
      $params[] = (int)$data['is_active'];
    }

    // Permitir setear next_run_at manual (opcional)
    if (array_key_exists('next_run_at', $data)) {
      $set[] = "next_run_at = ?";
      $params[] = $data['next_run_at'];
    }

    // Si mandan rule/rule_json => actualizar rule_json y recalcular next_run_at (si no lo mandaron)
    if (array_key_exists('rule', $data) || array_key_exists('rule_json', $data)) {
      $rule = $data['rule'] ?? $data['rule_json'];
      $ruleArr = self::normalizeRule($rule, $defaultTz);

      $ruleJson = json_encode($ruleArr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      if ($ruleJson === false) throw new \RuntimeException('rule inválida');

      $set[] = "rule_json = ?";
      $params[] = $ruleJson;

      if (!array_key_exists('next_run_at', $data)) {
        $set[] = "next_run_at = ?";
        $params[] = self::computeFirstNextRunAt($ruleArr);
      }
    }

    if (empty($set)) return self::get($dbCfg, $userId, $recId);

    $set[] = "updated_at = NOW()";
    $params[] = $recId;

    DB::exec(
      "UPDATE task_recurring SET " . implode(', ', $set) . " WHERE id = ?",
      $params,
      $dbCfg
    );

    return self::get($dbCfg, $userId, $recId);
  }

  public static function delete(array $dbCfg, int $userId, int $recId): void
  {
    self::getRowOwned($dbCfg, $userId, $recId);
    DB::exec("DELETE FROM task_recurring WHERE id = ?", [$recId], $dbCfg);
  }

  public static function get(array $dbCfg, int $userId, int $recId): array
  {
    $row = DB::fetchOne(
      "SELECT tr.id, tr.task_id, tr.rule_json, tr.next_run_at, tr.last_run_at, tr.is_active, tr.created_at, tr.updated_at,
              t.titulo AS template_titulo, t.project_id AS template_project_id
       FROM task_recurring tr
       INNER JOIN tasks t ON t.id = tr.task_id
       WHERE tr.id = ? AND t.user_id = ? AND t.deleted_at IS NULL
       LIMIT 1",
      [$recId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Regla no encontrada');
    return $row;
  }

  private static function getRowOwned(array $dbCfg, int $userId, int $recId): array
  {
    $row = DB::fetchOne(
      "SELECT tr.*
       FROM task_recurring tr
       INNER JOIN tasks t ON t.id = tr.task_id
       WHERE tr.id = ? AND t.user_id = ? AND t.deleted_at IS NULL
       LIMIT 1",
      [$recId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Regla no encontrada');
    return $row;
  }

  private static function ensureTaskOwned(array $dbCfg, int $userId, int $taskId): void
  {
    $row = DB::fetchOne(
      "SELECT id FROM tasks WHERE id=? AND user_id=? AND deleted_at IS NULL LIMIT 1",
      [$taskId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Tarea plantilla no encontrada');
  }

  /* =========================
     RUNNER (CRON)
     ========================= */

  public static function runDue(array $dbCfg, string $defaultTz, int $limit = 50): int
  {
    // Trae reglas vencidas (por seguridad une tasks para traer user_id y template)
    $rows = DB::fetchAll(
      "SELECT tr.id, tr.task_id, tr.rule_json, tr.next_run_at, tr.last_run_at, tr.is_active,
              t.user_id, t.project_id, t.parent_id, t.titulo, t.descripcion, t.prioridad
       FROM task_recurring tr
       INNER JOIN tasks t ON t.id = tr.task_id
       WHERE tr.is_active = 1
         AND tr.next_run_at IS NOT NULL
         AND tr.next_run_at <= NOW()
         AND t.deleted_at IS NULL
       ORDER BY tr.next_run_at ASC
       LIMIT " . (int)$limit,
      [],
      $dbCfg
    );

    $created = 0;

    foreach ($rows as $r) {
      // “claim” para evitar duplicados si corre cron doble:
      // Solo procesa si last_run_at es NULL o menor que next_run_at
      $claimed = DB::exec(
        "UPDATE task_recurring
         SET last_run_at = NOW(), updated_at = NOW()
         WHERE id = ?
           AND (last_run_at IS NULL OR last_run_at < next_run_at)
           AND is_active = 1
           AND next_run_at IS NOT NULL
           AND next_run_at <= NOW()",
        [(int)$r['id']],
        $dbCfg
      );

        if ($claimed !== 1) {
          continue; // ✅ otro proceso ya la reclamó o ya no aplica
        }
      // DB::exec devuelve filas afectadas? (en tu DB helper devuelve void)
      // Si tu DB helper no retorna rowcount, igual funciona aunque duplique si cron simultáneo.
      // Si quieres 100% anti-duplicados: ajustamos DB::exec para retornar rowCount().
      // Por ahora seguimos.

      $ruleArr = self::normalizeRule($r['rule_json'], $defaultTz);

      // 1) clonar task plantilla
      $newTitle = self::buildInstanceTitle((string)$r['titulo'], $ruleArr);

      $newTaskId = DB::insertGetId(
        "INSERT INTO tasks (user_id, project_id, parent_id, titulo, descripcion, prioridad, estado, start_at, due_at, done_at, is_star, orden, created_at, updated_at, deleted_at)
         VALUES (?, ?, NULL, ?, ?, ?, 'todo', NULL, NULL, NULL, 0, 0, NOW(), NOW(), NULL)",
        [
          (int)$r['user_id'],
          $r['project_id'] ?? null,
          $newTitle,
          $r['descripcion'] ?? null,
          $r['prioridad'] ?? 'med',
        ],
        $dbCfg
      );

      // 2) copiar tags (si rule.create.copy_tags = true)
      $copyTags = (bool)($ruleArr['create']['copy_tags'] ?? false);
      if ($copyTags) {
        DB::exec(
          "INSERT IGNORE INTO task_tags (task_id, tag_id)
           SELECT ?, tag_id FROM task_tags WHERE task_id = ?",
          [(int)$newTaskId, (int)$r['task_id']],
          $dbCfg
        );
      }

      // 3) calcular próximo next_run_at y actualizar regla
      $next = self::computeNextRunFrom((string)$r['next_run_at'], $ruleArr);

      DB::exec(
        "UPDATE task_recurring
         SET next_run_at = ?, updated_at = NOW()
         WHERE id = ?",
        [$next, (int)$r['id']],
        $dbCfg
      );

      $created++;
    }

    return $created;
  }

  /* =========================
     RULE + SCHEDULER
     ========================= */

  private static function normalizeRule($rule, string $defaultTz): array
  {
    // rule puede ser string JSON o array
    if (is_string($rule)) {
      $decoded = json_decode($rule, true);
      if (!is_array($decoded)) throw new \RuntimeException('rule_json inválido');
      $rule = $decoded;
    }

    if (!is_array($rule)) throw new \RuntimeException('rule es requerido');

    $freq = (string)($rule['freq'] ?? '');
    if (!in_array($freq, ['daily','weekly','monthly'], true)) {
      throw new \RuntimeException('rule.freq inválido (daily|weekly|monthly)');
    }

    $interval = (int)($rule['interval'] ?? 1);
    if ($interval < 1) $interval = 1;

    $tz = (string)($rule['timezone'] ?? $defaultTz);
    if ($tz === '') $tz = $defaultTz;

    $at = (string)($rule['at_time'] ?? '08:00:00');
    if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $at)) {
      throw new \RuntimeException('rule.at_time inválido (HH:MM:SS)');
    }

    $out = [
      'freq' => $freq,
      'interval' => $interval,
      'timezone' => $tz,
      'at_time' => $at,
      'by_weekday' => $rule['by_weekday'] ?? null,  // array [1..7]
      'by_monthday' => $rule['by_monthday'] ?? null, // int 1..31
      'create' => $rule['create'] ?? [],
    ];

    // normalizar by_weekday
    if ($out['by_weekday'] !== null && is_array($out['by_weekday'])) {
      $days = [];
      foreach ($out['by_weekday'] as $d) {
        $n = (int)$d;
        if ($n >= 1 && $n <= 7) $days[] = $n;
      }
      $days = array_values(array_unique($days));
      sort($days);
      $out['by_weekday'] = $days;
    } else {
      $out['by_weekday'] = null;
    }

    // normalizar by_monthday
    if ($out['by_monthday'] !== null && $out['by_monthday'] !== '') {
      $md = (int)$out['by_monthday'];
      $out['by_monthday'] = max(1, min(31, $md));
    } else {
      $out['by_monthday'] = null;
    }

    return $out;
  }

  private static function computeFirstNextRunAt(array $rule): string
  {
    $tz = new \DateTimeZone($rule['timezone']);
    $now = new \DateTimeImmutable('now', $tz);

    [$H,$M,$S] = array_map('intval', explode(':', $rule['at_time']));
    $base = $now->setTime($H, $M, $S);

    // si ya pasó hoy, arranca desde “ahora” para que computeNext avance
    if ($base <= $now) {
      $base = $now->add(new \DateInterval('PT1M'));
    }

    return self::computeNextRunFrom($base->format('Y-m-d H:i:s'), $rule);
  }

  private static function computeNextRunFrom(string $from, array $rule): string
  {
    $tz = new \DateTimeZone($rule['timezone']);
    $dt = new \DateTimeImmutable($from, $tz);

    [$H,$M,$S] = array_map('intval', explode(':', $rule['at_time']));
    $dt = $dt->setTime($H, $M, $S);

    $freq = $rule['freq'];
    $interval = (int)$rule['interval'];

    if ($freq === 'daily') {
      $dt = $dt->add(new \DateInterval('P' . max(1,$interval) . 'D'));
      return $dt->format('Y-m-d H:i:s');
    }

    if ($freq === 'weekly') {
      $days = $rule['by_weekday'] ?? [1];
      if (!$days) $days = [1];

      $candidate = $dt;
      for ($i=0; $i<14; $i++) {
        $candidate = $candidate->add(new \DateInterval('P1D'));
        $w = (int)$candidate->format('N'); // 1..7
        if (in_array($w, $days, true)) {
          return $candidate->format('Y-m-d H:i:s');
        }
      }

      // fallback: salto por interval semanas
      $candidate = $dt->add(new \DateInterval('P' . max(1,$interval*7) . 'D'));
      return $candidate->format('Y-m-d H:i:s');
    }

    // monthly
    $day = $rule['by_monthday'] ?? (int)$dt->format('d');
    $day = max(1, min(31, (int)$day));

    $candidate = $dt->add(new \DateInterval('P' . max(1,$interval) . 'M'));

    $y = (int)$candidate->format('Y');
    $m = (int)$candidate->format('m');

    $last = (int)(new \DateTimeImmutable("$y-$m-01", $tz))->modify('last day of this month')->format('d');
    $d = min($day, $last);

    $candidate = $candidate->setDate($y, $m, $d)->setTime($H, $M, $S);
    return $candidate->format('Y-m-d H:i:s');
  }

  private static function buildInstanceTitle(string $templateTitle, array $rule): string
  {
    $prefix = (string)($rule['create']['title_prefix'] ?? '');
    return $prefix !== '' ? ($prefix . ' ' . $templateTitle) : $templateTitle;
  }
}
