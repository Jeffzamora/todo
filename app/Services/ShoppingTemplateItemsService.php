<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class ShoppingTemplateItemsService
{
  private static function ensureTemplateOwned(array $dbCfg, int $userId, int $templateId): void
  {
    $row = DB::fetchOne(
      "SELECT id FROM shopping_templates WHERE id=? AND user_id=? LIMIT 1",
      [$templateId, $userId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Template no encontrado');
  }

  /**
   * Normaliza prioridad a los valores soportados por la BD:
   * none | low | medium | high
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

  public static function list(array $dbCfg, int $userId, int $templateId): array
  {
    self::ensureTemplateOwned($dbCfg, $userId, $templateId);

    return DB::fetchAll(
      "SELECT id, template_id, nombre, cantidad, unidad, categoria, marca, prioridad, orden, created_at
       FROM shopping_template_items
       WHERE template_id = ?
       ORDER BY orden ASC, id ASC",
      [$templateId],
      $dbCfg
    );
  }

  public static function create(array $dbCfg, int $userId, int $templateId, array $data): array
  {
    self::ensureTemplateOwned($dbCfg, $userId, $templateId);

    $nombre = trim((string)($data['nombre'] ?? ''));
    if ($nombre === '') throw new \RuntimeException('nombre es requerido');

    $cantidad = self::toNullableFloat($data['cantidad'] ?? null);
    $unidad = $data['unidad'] ?? null;
    $categoria = $data['categoria'] ?? null;
    $marca = $data['marca'] ?? null;
    $prioridad = self::normalizePrioridad($data['prioridad'] ?? null);
    $orden = (int)($data['orden'] ?? 0);

    $id = DB::insertGetId(
      "INSERT INTO shopping_template_items (template_id, nombre, cantidad, unidad, categoria, marca, prioridad, orden)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
      [$templateId, $nombre, $cantidad, $unidad, $categoria, $marca, $prioridad, $orden],
      $dbCfg
    );

    return DB::fetchOne(
      "SELECT id, template_id, nombre, cantidad, unidad, categoria, marca, prioridad, orden, created_at
       FROM shopping_template_items WHERE id=? AND template_id=? LIMIT 1",
      [$id, $templateId],
      $dbCfg
    ) ?? [];
  }

  public static function update(array $dbCfg, int $userId, int $templateId, int $itemId, array $data): array
  {
    self::ensureTemplateOwned($dbCfg, $userId, $templateId);

    $row = DB::fetchOne(
      "SELECT id FROM shopping_template_items WHERE id=? AND template_id=? LIMIT 1",
      [$itemId, $templateId],
      $dbCfg
    );
    if (!$row) throw new \RuntimeException('Item no encontrado');

    $allowed = ['nombre','cantidad','unidad','categoria','marca','prioridad','orden'];
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

        case 'prioridad':
          $set[] = "prioridad = ?";
          $params[] = self::normalizePrioridad($data[$k]);
          break;

        case 'orden':
          $set[] = "orden = ?";
          $params[] = (int)$data[$k];
          break;

        default:
          $set[] = "$k = ?";
          $params[] = $data[$k];
      }
    }

    if (!empty($set)) {
      $params[] = $itemId;
      $params[] = $templateId;
      DB::exec(
        "UPDATE shopping_template_items SET " . implode(', ', $set) . " WHERE id=? AND template_id=?",
        $params,
        $dbCfg
      );
    }

    return DB::fetchOne(
      "SELECT id, template_id, nombre, cantidad, unidad, categoria, marca, prioridad, orden, created_at
       FROM shopping_template_items WHERE id=? AND template_id=? LIMIT 1",
      [$itemId, $templateId],
      $dbCfg
    ) ?? [];
  }

  public static function delete(array $dbCfg, int $userId, int $templateId, int $itemId): void
  {
    self::ensureTemplateOwned($dbCfg, $userId, $templateId);

    DB::exec(
      "DELETE FROM shopping_template_items WHERE id=? AND template_id=?",
      [$itemId, $templateId],
      $dbCfg
    );
  }
}
