<?php
declare(strict_types=1);

namespace App\Security;

final class Jwt
{
  private static function b64urlEncode(string $data): string
  {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

  private static function b64urlDecode(string $data): string
  {
    $remainder = strlen($data) % 4;
    if ($remainder) $data .= str_repeat('=', 4 - $remainder);
    return base64_decode(strtr($data, '-_', '+/')) ?: '';
  }

  public static function sign(array $payload, string $secret): string
  {
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $h = self::b64urlEncode(json_encode($header, JSON_UNESCAPED_UNICODE));
    $p = self::b64urlEncode(json_encode($payload, JSON_UNESCAPED_UNICODE));
    $sig = hash_hmac('sha256', $h . '.' . $p, $secret, true);
    return $h . '.' . $p . '.' . self::b64urlEncode($sig);
  }

  public static function verify(string $jwt, string $secret): array
  {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
      throw new \RuntimeException('Token inválido');
    }
    [$h, $p, $s] = $parts;

    $sigCheck = self::b64urlEncode(hash_hmac('sha256', $h . '.' . $p, $secret, true));
    if (!hash_equals($sigCheck, $s)) {
      throw new \RuntimeException('Firma inválida');
    }

    $payload = json_decode(self::b64urlDecode($p), true);
    if (!is_array($payload)) {
      throw new \RuntimeException('Payload inválido');
    }

    $now = time();
    if (isset($payload['nbf']) && $now < (int)$payload['nbf']) {
      throw new \RuntimeException('Token no válido aún');
    }
    if (isset($payload['exp']) && $now >= (int)$payload['exp']) {
      throw new \RuntimeException('Token expirado');
    }

    return $payload;
  }
}
