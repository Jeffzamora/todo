<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class ShoppingItemsService
{
  private static function ensureListOwned(array $dbCfg, int $userId, int $listId): void
  {
    $row = DB::fetchOne(
      "SELECT id FROM shopping_lists WHERE id = ? AND user_id = ? LIMIT 1",
      [$listId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Lista no encontrada');
  }

  /**
   * Normaliza prioridad a los valores soportados por la BD:
   * none | low | medium | high
   *
   * Acepta compatibilidad:
   * - must -> high
   * - optional -> low
   * - baja/media/alta/sin prioridad
   * - 0/1/2/3
   */
  private static function normalizePrioridad(mixed $v): string
  {
    $x = strtolower(trim((string)$v));

    if ($x === '' || $x === 'none' || $x === 'sin prioridad' || $x === 'sin' || $x === '0') return 'none';
    if ($x === 'low' || $x === 'baja' || $x === '1') return 'low';
    if ($x === 'medium' || $x === 'media' || $x === '2') return 'medium';
    if ($x === 'high' || $x === 'alta' || $x === '3') return 'high';

    // compatibilidad con esquema viejo
    if ($x === 'must') return 'high';
    if ($x === 'optional') return 'low';

    return 'none';
  }

  private static function toNullableFloat(mixed $v): ?float
  {
    if ($v === null) return null;
    if (is_string($v) && trim($v) === '') return null;
    return (float)$v;
  }

  public static function list(array $dbCfg, int $userId, int $listId): array
  {
    self::ensureListOwned($dbCfg, $userId, $listId);

    return DB::fetchAll(
      "SELECT id, list_id, nombre, cantidad, unidad, categoria, marca,
              precio_estimado, precio_real, is_checked, prioridad, orden,
              created_at, updated_at
       FROM shopping_items
       WHERE list_id = ?
       ORDER BY is_checked ASC, orden ASC, id DESC",
      [$listId],
      $dbCfg
    );
  }

  public static function create(array $dbCfg, int $userId, int $listId, array $data): array
  {
    self::ensureListOwned($dbCfg, $userId, $listId);

    $nombre = trim((string)($data['nombre'] ?? ''));
    if ($nombre === '') throw new \RuntimeException('nombre es requerido');

    $cantidad = self::toNullableFloat($data['cantidad'] ?? null);
    $unidad = $data['unidad'] ?? null;
    $categoria = $data['categoria'] ?? null;
    $marca = $data['marca'] ?? null;
    $precioE = self::toNullableFloat($data['precio_estimado'] ?? null);
    $precioR = self::toNullableFloat($data['precio_real'] ?? null);
    $isChecked = (int)($data['is_checked'] ?? 0);
    $prioridad = self::normalizePrioridad($data['prioridad'] ?? null);
    $orden = (int)($data['orden'] ?? 0);

    $id = DB::insertGetId(
      "INSERT INTO shopping_items
        (list_id, nombre, cantidad, unidad, categoria, marca, precio_estimado, precio_real, is_checked, prioridad, orden)
       VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
      [$listId, $nombre, $cantidad, $unidad, $categoria, $marca, $precioE, $precioR, $isChecked, $prioridad, $orden],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, list_id, nombre, cantidad, unidad, categoria, marca,
              precio_estimado, precio_real, is_checked, prioridad, orden,
              created_at, updated_at
       FROM shopping_items
       WHERE id = ? AND list_id = ? LIMIT 1",
      [$id, $listId],
      $dbCfg
    ) ?? [];
  }

  public static function update(array $dbCfg, int $userId, int $listId, int $itemId, array $data): array
  {
    self::ensureListOwned($dbCfg, $userId, $listId);

    $row = DB::fetchOne(
      "SELECT id FROM shopping_items WHERE id = ? AND list_id = ? LIMIT 1",
      [$itemId, $listId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Item no encontrado');

    $allowed = [
      'nombre','cantidad','unidad','categoria','marca',
      'precio_estimado','precio_real','is_checked','prioridad','orden'
    ];

    $set = [];
    $params = [];

    foreach ($allowed as $k) {
      if (!array_key_exists($k, $data)) continue;

      switch ($k) {
        case 'nombre':
          $v = trim((string)$data[$k]);
          if ($v === '') throw new \RuntimeException('nombre es requerido');
          $set[] = "nombre = ?";
          $params[] = $v;
          break;

        case 'cantidad':
          $set[] = "cantidad = ?";
          $params[] = self::toNullableFloat($data[$k]);
          break;

        case 'precio_estimado':
          $set[] = "precio_estimado = ?";
          $params[] = self::toNullableFloat($data[$k]);
          break;

        case 'precio_real':
          $set[] = "precio_real = ?";
          $params[] = self::toNullableFloat($data[$k]);
          break;

        case 'is_checked':
          $set[] = "is_checked = ?";
          $params[] = ((int)$data[$k]) ? 1 : 0;
          break;

        case 'prioridad':
          $set[] = "prioridad = ?";
          $params[] = self::normalizePrioridad($data[$k]);
          break;

        case 'orden':
          $set[] = "orden = ?";
          $params[] = (int)$data[$k];
          break;

        default:
          // unidad/categoria/marca: permitir null
          $set[] = "$k = ?";
          $params[] = $data[$k];
      }
    }

    if (empty($set)) {
      return DB::fetchOne(
        "SELECT id, list_id, nombre, cantidad, unidad, categoria, marca,
                precio_estimado, precio_real, is_checked, prioridad, orden,
                created_at, updated_at
         FROM shopping_items WHERE id=? AND list_id=? LIMIT 1",
        [$itemId, $listId],
        $dbCfg
      ) ?? [];
    }

    $set[] = "updated_at = NOW()";
    $params[] = $itemId;
    $params[] = $listId;

    DB::exec(
      "UPDATE shopping_items SET " . implode(', ', $set) . " WHERE id=? AND list_id=?",
      $params,
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, list_id, nombre, cantidad, unidad, categoria, marca,
              precio_estimado, precio_real, is_checked, prioridad, orden,
              created_at, updated_at
       FROM shopping_items WHERE id=? AND list_id=? LIMIT 1",
      [$itemId, $listId],
      $dbCfg
    ) ?? [];
  }

  public static function toggleChecked(array $dbCfg, int $userId, int $listId, int $itemId, int $checked): array
  {
    self::ensureListOwned($dbCfg, $userId, $listId);

    DB::exec(
      "UPDATE shopping_items SET is_checked = ?, updated_at = NOW()
       WHERE id = ? AND list_id = ?",
      [$checked ? 1 : 0, $itemId, $listId],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, list_id, nombre, cantidad, unidad, categoria, marca,
              precio_estimado, precio_real, is_checked, prioridad, orden,
              created_at, updated_at
       FROM shopping_items
       WHERE id = ? AND list_id = ? LIMIT 1",
      [$itemId, $listId],
      $dbCfg
    ) ?? [];
  }

  public static function delete(array $dbCfg, int $userId, int $listId, int $itemId): void
  {
    self::ensureListOwned($dbCfg, $userId, $listId);

    DB::exec(
      "DELETE FROM shopping_items WHERE id = ? AND list_id = ?",
      [$itemId, $listId],
      $dbCfg
    );
  }
}
