<?php
declare(strict_types=1);

namespace App\Config;

final class Config
{
  public static function env(string $key, ?string $default = null): ?string
  {
    // primero: variable de entorno real (hosting)
    $v = getenv($key);
    if ($v !== false) return $v;

    // fallback: $_ENV cargado por nuestro loader
    if (isset($_ENV[$key])) return (string)$_ENV[$key];

    return $default;
  }

  public static function bool(string $key, bool $default = false): bool
  {
    $v = self::env($key);
    if ($v === null) return $default;
    $v = strtolower(trim($v));
    return in_array($v, ['1','true','yes','on'], true);
  }

  public static function int(string $key, int $default = 0): int
  {
    $v = self::env($key);
    if ($v === null || $v === '') return $default;
    return (int)$v;
  }

  public static function db(): array
  {
    return [
      'host' => self::env('DB_HOST', '127.0.0.1'),
      'port' => self::env('DB_PORT', '3306'),
      'name' => self::env('DB_NAME', 'todo_pro'),
      'user' => self::env('DB_USER', 'root'),
      'pass' => self::env('DB_PASS', ''),
      'charset' => self::env('DB_CHARSET', 'utf8mb4'),
    ];
  }
}
