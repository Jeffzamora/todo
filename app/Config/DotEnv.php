<?php
declare(strict_types=1);

namespace App\Config;

final class DotEnv
{
  public static function load(string $path): void
  {
    if (!is_file($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) return;

    foreach ($lines as $line) {
      $line = trim($line);
      if ($line === '' || str_starts_with($line, '#')) continue;

      $pos = strpos($line, '=');
      if ($pos === false) continue;

      $key = trim(substr($line, 0, $pos));
      $key = preg_replace('/^export\s+/i', '', $key);
      $val = trim(substr($line, $pos + 1));

      // quitar comillas simples/dobles
      if ((str_starts_with($val, '"') && str_ends_with($val, '"')) ||
          (str_starts_with($val, "'") && str_ends_with($val, "'"))) {
        $val = substr($val, 1, -1);
      }

      $_ENV[$key] = $val;
      // para hosting / getenv
      if (getenv($key) === false) {
        putenv($key . '=' . $val);
      }
    }
  }
}
