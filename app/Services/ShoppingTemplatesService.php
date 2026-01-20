<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class ShoppingTemplatesService
{
  public static function list(array $dbCfg, int $userId): array
  {
    return DB::fetchAll(
      "SELECT id, user_id, nombre, created_at
       FROM shopping_templates
       WHERE user_id = ?
       ORDER BY id DESC",
      [$userId],
      $dbCfg
    );
  }

  public static function create(array $dbCfg, int $userId, array $data): array
  {
    $nombre = trim((string)($data['nombre'] ?? ''));
    if ($nombre === '') throw new \RuntimeException('nombre es requerido');

    $id = DB::insertGetId(
      "INSERT INTO shopping_templates (user_id, nombre) VALUES (?, ?)",
      [$userId, $nombre],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, user_id, nombre, created_at
       FROM shopping_templates WHERE id=? AND user_id=? LIMIT 1",
      [$id, $userId],
      $dbCfg
    ) ?? [];
  }

  public static function delete(array $dbCfg, int $userId, int $templateId): void
  {
    self::ensureOwned($dbCfg, $userId, $templateId);

    DB::begin($dbCfg);
    try {
      DB::exec("DELETE FROM shopping_template_items WHERE template_id=?", [$templateId], $dbCfg);
      DB::exec("DELETE FROM shopping_templates WHERE id=? AND user_id=?", [$templateId, $userId], $dbCfg);
      DB::commit($dbCfg);
    } catch (\Throwable $e) {
      DB::rollBack($dbCfg);
      throw $e;
    }
  }

  public static function createListFromTemplate(array $dbCfg, int $userId, int $templateId, array $payload): array
  {
    self::ensureOwned($dbCfg, $userId, $templateId);

    $nombre = trim((string)($payload['nombre'] ?? 'Lista desde template'));
    $tienda = $payload['tienda'] ?? null;
    $presupuesto = array_key_exists('presupuesto', $payload) ? $payload['presupuesto'] : null;
    $presupuesto = ($presupuesto === null || $presupuesto === '') ? null : (float)$presupuesto;
    $currency = strtoupper((string)($payload['currency'] ?? 'USD'));

    DB::begin($dbCfg);
    try {
      $listId = DB::insertGetId(
        "INSERT INTO shopping_lists (user_id, nombre, tienda, presupuesto, currency, estado)
         VALUES (?, ?, ?, ?, ?, 'open')",
        [$userId, $nombre, $tienda, $presupuesto, $currency],
        $dbCfg
      );

      DB::exec(
        "INSERT INTO shopping_items (list_id, nombre, cantidad, unidad, categoria, marca, prioridad, orden, created_at, updated_at)
         SELECT ?, nombre, cantidad, unidad, categoria, marca, prioridad, orden, NOW(), NOW()
         FROM shopping_template_items
         WHERE template_id = ?
         ORDER BY orden ASC, id ASC",
        [$listId, $templateId],
        $dbCfg
      );

      DB::commit($dbCfg);

      $list = DB::fetchOne(
        "SELECT * FROM shopping_lists WHERE id=? AND user_id=? LIMIT 1",
        [$listId, $userId],
        $dbCfg
      );

      return ['list' => $list, 'list_id' => $listId];
    } catch (\Throwable $e) {
      DB::rollBack($dbCfg);
      throw $e;
    }
  }

  private static function ensureOwned(array $dbCfg, int $userId, int $templateId): void
  {
    $row = DB::fetchOne(
      "SELECT id FROM shopping_templates WHERE id=? AND user_id=? LIMIT 1",
      [$templateId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Template no encontrado');
  }
}
