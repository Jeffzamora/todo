<?php
declare(strict_types=1);

namespace App\Utils;

final class Request
{
  public static function method(): string
  {
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
  }

  public static function path(): string
  {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $qpos = strpos($uri, '?');
    if ($qpos !== false) $uri = substr($uri, 0, $qpos);
    return rtrim($uri, '/') ?: '/';
  }

  public static function headers(): array
  {
    // getallheaders() no siempre existe en todos los entornos
    if (function_exists('getallheaders')) {
      $h = getallheaders();
      return is_array($h) ? $h : [];
    }

    $headers = [];
    foreach ($_SERVER as $k => $v) {
      if (str_starts_with($k, 'HTTP_')) {
        $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($k, 5)))));
        $headers[$name] = $v;
      }
    }
    return $headers;
  }

  public static function bodyRaw(): string
  {
    return file_get_contents('php://input') ?: '';
  }

public static function json(): array
{
  $raw = file_get_contents('php://input');
  if ($raw === false || trim($raw) === '') return [];

  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}


  /**
   * Devuelve body como array, aceptando:
   * - JSON (application/json)
   * - x-www-form-urlencoded
   * - multipart/form-data
   */
  public static function input(): array
  {
    $headers = self::headers();
    $ct = $headers['Content-Type'] ?? $headers['content-type'] ?? ($_SERVER['CONTENT_TYPE'] ?? '');
    $ct = strtolower((string)$ct);

    // JSON
    if (str_contains($ct, 'application/json')) {
      $data = self::json();
      if (!empty($data)) return $data;
      return [];
    }

    // Form-data / urlencoded
    if (!empty($_POST)) return $_POST;

    // Fallback: intentar parsear urlencoded desde raw
    $raw = self::bodyRaw();
    if ($raw !== '' && str_contains($ct, 'application/x-www-form-urlencoded')) {
      parse_str($raw, $out);
      return is_array($out) ? $out : [];
    }

    // Último fallback: intentar JSON aunque no venga content-type
    $try = json_decode($raw, true);
    return is_array($try) ? $try : [];
  }

  public static function query(string $key, ?string $default = null): ?string
  {
    return isset($_GET[$key]) ? (string)$_GET[$key] : $default;
  }
}
