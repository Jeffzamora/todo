<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Security\Jwt;

final class AuthMiddleware
{
  public static function bearerToken(): ?string
  {
    $hdr = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['Authorization'] ?? '';
    if (!$hdr) return null;

    if (preg_match('/Bearer\s+(.+)/i', $hdr, $m)) {
      return trim($m[1]);
    }
    return null;
  }

  public static function requireAuth(array $jwtCfg): array
  {
    $token = self::bearerToken();
    if (!$token) {
      throw new \RuntimeException('Falta Authorization Bearer token',401);
    }
    $payload = Jwt::verify($token, (string)$jwtCfg['secret']);

    if (($payload['iss'] ?? '') !== ($jwtCfg['iss'] ?? '')) {
  throw new \RuntimeException('Issuer inválido',401);
}
if (($payload['aud'] ?? '') !== ($jwtCfg['aud'] ?? '')) {
  throw new \RuntimeException('Audience inválido',401);
}


    // puedes guardar aquí info “global” si te gusta
    // $_SERVER['AUTH_USER_ID'] = (string)($payload['sub'] ?? '');

    return $payload;
  }
}
