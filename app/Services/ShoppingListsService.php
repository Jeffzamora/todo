<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class ShoppingListsService
{
  public static function list(array $dbCfg, int $userId): array
  {
    return DB::fetchAll(
      "SELECT id, nombre, tienda, presupuesto, currency, estado, created_at, updated_at
       FROM shopping_lists
       WHERE user_id = ?
       ORDER BY (estado='open') DESC, id DESC",
      [$userId],
      $dbCfg
    );
  }

  public static function create(array $dbCfg, int $userId, array $data): array
  {
    $nombre = trim((string)($data['nombre'] ?? ''));
    if ($nombre === '') throw new \RuntimeException('nombre es requerido');

    $tienda = $data['tienda'] ?? null;
    $presupuesto = array_key_exists('presupuesto', $data) ? (float)$data['presupuesto'] : null;
    $currency = (string)($data['currency'] ?? 'USD');

    $id = DB::insertGetId(
      "INSERT INTO shopping_lists (user_id, nombre, tienda, presupuesto, currency, estado)
       VALUES (?, ?, ?, ?, ?, 'open')",
      [$userId, $nombre, $tienda, $presupuesto, $currency],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, nombre, tienda, presupuesto, currency, estado, created_at, updated_at
       FROM shopping_lists WHERE id = ? AND user_id = ? LIMIT 1",
      [$id, $userId],
      $dbCfg
    ) ?? [];
  }

public static function update(array $dbCfg, int $userId, int $listId, array $data): array
{
  $row = DB::fetchOne(
    "SELECT id FROM shopping_lists WHERE id = ? AND user_id = ? LIMIT 1",
    [$listId, $userId],
    $dbCfg
  );
  if (!$row) throw new \RuntimeException('Lista no encontrada');

  $allowed = ['nombre','tienda','presupuesto','currency','estado'];

  $set = [];
  $params = [];

  foreach ($allowed as $k) {
    if (array_key_exists($k, $data)) {
      $set[] = "$k = ?";
      $params[] = $data[$k]; // puede ser NULL ✅
    }
  }

  if (empty($set)) {
    return DB::fetchOne(
      "SELECT id, nombre, tienda, presupuesto, currency, estado, created_at, updated_at
       FROM shopping_lists WHERE id=? AND user_id=? LIMIT 1",
      [$listId, $userId],
      $dbCfg
    ) ?? [];
  }

  $set[] = "updated_at = NOW()";
  $params[] = $listId;
  $params[] = $userId;

  DB::exec(
    "UPDATE shopping_lists SET " . implode(', ', $set) . " WHERE id=? AND user_id=?",
    $params,
    $dbCfg
  );

  return DB::fetchOne(
    "SELECT id, nombre, tienda, presupuesto, currency, estado, created_at, updated_at
     FROM shopping_lists WHERE id=? AND user_id=? LIMIT 1",
    [$listId, $userId],
    $dbCfg
  ) ?? [];
}


  public static function delete(array $dbCfg, int $userId, int $listId): void
  {
    DB::exec(
      "DELETE FROM shopping_lists WHERE id = ? AND user_id = ?",
      [$listId, $userId],
      $dbCfg
    );
  }
}
