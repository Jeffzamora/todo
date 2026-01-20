<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;

final class UsersService
{
  public static function getMe(array $dbCfg, int $userId): array
  {
    $u = DB::fetchOne(
      "SELECT id, username, email, nombre, apellido, cargo, estado, ultimo_login_at, created_at, updated_at
       FROM usuarios
       WHERE id = ?
       LIMIT 1",
      [$userId],
      $dbCfg
    );
    if (!$u) throw new \RuntimeException('Usuario no encontrado', 404);

    return [
      'id' => (int)$u['id'],
      'username' => $u['username'] ?? null,
      'email' => $u['email'] ?? null,
      'nombre' => $u['nombre'] ?? null,
      'apellido' => $u['apellido'] ?? null,
      'cargo' => $u['cargo'] ?? null,
      'estado' => $u['estado'] ?? null,
      'ultimo_login_at' => $u['ultimo_login_at'] ?? null,
      'created_at' => $u['created_at'] ?? null,
      'updated_at' => $u['updated_at'] ?? null,
    ];
  }

  public static function updateMe(array $dbCfg, int $userId, array $data): array
  {
    $allowed = ['nombre', 'apellido', 'email'];
    $set = [];
    $params = [];

    foreach ($allowed as $k) {
      if (!array_key_exists($k, $data)) continue;
      $v = is_string($data[$k]) ? trim($data[$k]) : $data[$k];

      if ($k === 'email') {
        if ($v === null || $v === '') {
          // permitir limpiar email si el usuario quiere
          $set[] = "email = NULL";
          continue;
        }
        if (!filter_var((string)$v, FILTER_VALIDATE_EMAIL)) {
          throw new \RuntimeException('Email inválido', 422);
        }

        // evitar duplicados
        $exists = DB::fetchOne(
          "SELECT id FROM usuarios WHERE email = ? AND id <> ? LIMIT 1",
          [(string)$v, $userId],
          $dbCfg
        );
        if ($exists) throw new \RuntimeException('Ese email ya está en uso', 409);

        $set[] = "email = ?";
        $params[] = (string)$v;
        continue;
      }

      // nombre / apellido: permitir null o string
      if ($v === null || $v === '') {
        $set[] = "$k = NULL";
        continue;
      }

      if (!is_string($v)) throw new \RuntimeException("Campo '$k' inválido", 422);
      if (strlen($v) > 100) throw new \RuntimeException("Campo '$k' demasiado largo", 422);

      $set[] = "$k = ?";
      $params[] = $v;
    }

    if (empty($set)) {
      throw new \RuntimeException('No hay cambios para actualizar', 422);
    }

    // intentar siempre actualizar updated_at si existe en tu tabla
    $sql = "UPDATE usuarios SET " . implode(', ', $set) . ", updated_at = NOW() WHERE id = ?";
    $params[] = $userId;

    DB::exec($sql, $params, $dbCfg);

    return self::getMe($dbCfg, $userId);
  }

  public static function changePassword(array $dbCfg, array $refreshCfg, int $userId, string $currentPassword, string $newPassword): void
  {
    $u = DB::fetchOne(
      "SELECT id, password_hash FROM usuarios WHERE id = ? LIMIT 1",
      [$userId],
      $dbCfg
    );
    if (!$u) throw new \RuntimeException('Usuario no encontrado', 404);

    if (!password_verify($currentPassword, (string)($u['password_hash'] ?? ''))) {
      throw new \RuntimeException('Contraseña actual incorrecta', 401);
    }

    $newPassword = trim($newPassword);
    if (strlen($newPassword) < 8) throw new \RuntimeException('La nueva contraseña debe tener al menos 8 caracteres', 422);
    if ($newPassword === $currentPassword) throw new \RuntimeException('La nueva contraseña no puede ser igual a la actual', 422);

    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    if (!$hash) throw new \RuntimeException('No se pudo generar el hash de la contraseña', 500);

    DB::begin($dbCfg);
    try {
      DB::exec(
        "UPDATE usuarios SET password_hash = ?, updated_at = NOW() WHERE id = ?",
        [$hash, $userId],
        $dbCfg
      );

      // Seguridad: revocar todos los refresh tokens activos del usuario
      DB::exec(
        "UPDATE refresh_tokens SET revoked_at = NOW() WHERE user_id = ? AND revoked_at IS NULL",
        [$userId],
        $dbCfg
      );
      DB::commit($dbCfg);
    } catch (\Throwable $e) {
      DB::rollBack($dbCfg);
      throw $e;
    }
  }
}
