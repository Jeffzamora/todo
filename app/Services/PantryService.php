<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class PantryService
{
  public static function list(array $dbCfg, int $userId): array
  {
    return DB::fetchAll(
      "SELECT id, user_id, nombre, stock, unidad, min_stock, updated_at
       FROM pantry_items
       WHERE user_id = ?
       ORDER BY nombre ASC, id DESC",
      [$userId],
      $dbCfg
    );
  }

  public static function create(array $dbCfg, int $userId, array $data): array
  {
    $nombre = trim((string)($data['nombre'] ?? ''));
    if ($nombre === '') throw new \RuntimeException('nombre es requerido');

    $stock = (float)($data['stock'] ?? 0);
    $unidad = $data['unidad'] ?? null;
    $min = array_key_exists('min_stock', $data) ? $data['min_stock'] : null;
    $min = ($min === null || $min === '') ? null : (float)$min;

    $id = DB::insertGetId(
      "INSERT INTO pantry_items (user_id, nombre, stock, unidad, min_stock)
       VALUES (?, ?, ?, ?, ?)",
      [$userId, $nombre, $stock, $unidad, $min],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, user_id, nombre, stock, unidad, min_stock, updated_at
       FROM pantry_items WHERE id=? AND user_id=? LIMIT 1",
      [$id, $userId],
      $dbCfg
    ) ?? [];
  }

  public static function update(array $dbCfg, int $userId, int $id, array $data): array
  {
    self::ensureOwned($dbCfg, $userId, $id);

    $allowed = ['nombre','stock','unidad','min_stock'];
    $set = [];
    $params = [];

    foreach ($allowed as $k) {
      if (array_key_exists($k, $data)) {
        $set[] = "$k = ?";
        $params[] = $data[$k];
      }
    }

    if (empty($set)) return self::get($dbCfg, $userId, $id);

    $params[] = $id;
    $params[] = $userId;

    DB::exec("UPDATE pantry_items SET " . implode(', ', $set) . " WHERE id=? AND user_id=?", $params, $dbCfg);

    return self::get($dbCfg, $userId, $id);
  }

  public static function adjust(array $dbCfg, int $userId, int $id, float $delta): array
  {
    self::ensureOwned($dbCfg, $userId, $id);

    DB::exec(
      "UPDATE pantry_items SET stock = stock + ? WHERE id=? AND user_id=?",
      [$delta, $id, $userId],
      $dbCfg
    );

    return self::get($dbCfg, $userId, $id);
  }

  public static function delete(array $dbCfg, int $userId, int $id): void
  {
    self::ensureOwned($dbCfg, $userId, $id);
    DB::exec("DELETE FROM pantry_items WHERE id=? AND user_id=?", [$id, $userId], $dbCfg);
  }

  public static function get(array $dbCfg, int $userId, int $id): array
  {
    $row = DB::fetchOne(
      "SELECT id, user_id, nombre, stock, unidad, min_stock, updated_at
       FROM pantry_items WHERE id=? AND user_id=? LIMIT 1",
      [$id, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Item no encontrado');
    return $row;
  }

  private static function ensureOwned(array $dbCfg, int $userId, int $id): void
  {
    $row = DB::fetchOne("SELECT id FROM pantry_items WHERE id=? AND user_id=? LIMIT 1", [$id, $userId], $dbCfg);
    if (!$row) throw new \RuntimeException('Item no encontrado');
  }
}
