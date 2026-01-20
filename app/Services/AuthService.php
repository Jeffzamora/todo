<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\DB;
use App\Security\Jwt;

final class AuthService
{
  private static function now(): string { return gmdate('Y-m-d H:i:s'); }

  private static function randomToken(int $bytes = 32): string
  {
    return rtrim(strtr(base64_encode(random_bytes($bytes)), '+/', '-_'), '=');
  }

private static function hashToken(string $token, array $refreshCfg): string
{
  $secret = (string)($refreshCfg['secret'] ?? '');
  if ($secret === '') {
    throw new \RuntimeException('Falta REFRESH_SECRET en refreshCfg');
  }
  return hash_hmac('sha256', $token, $secret);
}


  public static function login(array $dbCfg, array $jwtCfg, array $refreshCfg, string $identifier, string $password, array $meta): array
  {
    $identifier = trim($identifier);

    $user = DB::fetchOne(
      "SELECT id, username, email, password_hash, estado
       FROM usuarios
       WHERE (username = ? OR email = ?)
       LIMIT 1",
      [$identifier, $identifier],
      $dbCfg
    );

    if (!$user || ($user['estado'] ?? '') !== 'activo') {
      throw new \RuntimeException('Credenciales inválidas');
    }
    if (!password_verify($password, (string)($user['password_hash'] ?? ''))) {
      throw new \RuntimeException('Credenciales inválidas');
    }

    DB::exec("UPDATE usuarios SET ultimo_login_at = NOW() WHERE id = ?", [(int)$user['id']], $dbCfg);

    $tokens = self::issueTokens($dbCfg, $jwtCfg, $refreshCfg, (int)$user['id'], $meta);

return array_merge([
  'user' => [
    'id' => (int)$user['id'],
    'username' => $user['username'],
    'email' => $user['email'],
  ],
], $tokens);
  }

  public static function refresh(array $dbCfg, array $jwtCfg, array $refreshCfg, string $refreshToken, array $meta): array
  {
    $hash = self::hashToken($refreshToken, $refreshCfg);
    $row = DB::fetchOne(
      "SELECT id, user_id, expires_at, revoked_at
       FROM refresh_tokens
       WHERE token_hash = ?
       LIMIT 1",
      [$hash],
      $dbCfg
    );

    if (!$row) throw new \RuntimeException('Refresh token inválido');
    if (!empty($row['revoked_at'])) throw new \RuntimeException('Refresh token revocado');

    // expires_at en UTC recomendado
    $expiresAt = strtotime((string)$row['expires_at']);
    if ($expiresAt !== false && time() >= $expiresAt) {
      throw new \RuntimeException('Refresh token expirado');
    }

    // ROTACIÓN: revocamos el viejo y emitimos uno nuevo
    DB::exec("UPDATE refresh_tokens SET revoked_at = NOW() WHERE id = ?", [(int)$row['id']], $dbCfg);

    return self::issueTokens($dbCfg, $jwtCfg, $refreshCfg, (int)$row['user_id'], $meta);
  }

public static function logout(array $dbCfg, array $refreshCfg, string $refreshToken): void
{
  $hash = self::hashToken($refreshToken, $refreshCfg);
  DB::exec("UPDATE refresh_tokens SET revoked_at = NOW() WHERE token_hash = ? AND revoked_at IS NULL", [$hash], $dbCfg);
}


  private static function issueTokens(array $dbCfg, array $jwtCfg, array $refreshCfg, int $userId, array $meta): array
  {
    $now = time();
    $ttlMin = (int)($jwtCfg['ttl_min'] ?? 15);
    $exp = $now + ($ttlMin * 60);

    $payload = [
      'iss' => (string)$jwtCfg['iss'],
      'aud' => (string)$jwtCfg['aud'],
      'sub' => (string)$userId,
      'iat' => $now,
      'exp' => $exp,
      'jti' => bin2hex(random_bytes(16)),
    ];

    $access = Jwt::sign($payload, (string)$jwtCfg['secret']);

    $refreshToken = self::randomToken(48);
    $refreshHash = self::hashToken($refreshToken,$refreshCfg);
    $refreshDays = (int)($refreshCfg['ttl_days'] ?? 30);
    $expiresAt = gmdate('Y-m-d H:i:s', time() + ($refreshDays * 86400));

    DB::insertGetId(
      "INSERT INTO refresh_tokens (user_id, token_hash, device_id, ip, user_agent, expires_at)
       VALUES (?, ?, ?, ?, ?, ?)",
      [
        $userId,
        $refreshHash,
        $meta['device_id'] ?? null,
        $meta['ip'] ?? null,
        $meta['user_agent'] ?? null,
        $expiresAt
      ],
      $dbCfg
    );

    return [
      'access_token' => $access,
      'token_type' => 'Bearer',
      'expires_in' => ($ttlMin * 60),
      'refresh_token' => $refreshToken,
    ];
  }
}
