<?php
declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

final class DB
{
  private static ?PDO $pdo = null;

  public static function pdo(array $cfg): PDO
  {
    if (self::$pdo instanceof PDO) return self::$pdo;

    $host = $cfg['host'];
    $port = $cfg['port'];
    $name = $cfg['name'];
    $user = $cfg['user'];
    $pass = $cfg['pass'];
    $charset = $cfg['charset'] ?? 'utf8mb4';

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

    try {
      self::$pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]);
      return self::$pdo;
    } catch (PDOException $e) {
      throw new \RuntimeException('Error conectando a DB: ' . $e->getMessage());
    }
  }

  public static function fetchOne(string $sql, array $params, array $cfg): ?array
  {
    $stmt = self::pdo($cfg)->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public static function fetchAll(string $sql, array $params, array $cfg): array
  {
    $stmt = self::pdo($cfg)->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll() ?: [];
  }

  public static function exec(string $sql, array $params = [], array $dbCfg = []): int
  {
    $pdo = self::pdo($dbCfg);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount(); // ✅ IMPORTANTE
  }


  public static function insertGetId(string $sql, array $params, array $cfg): int
  {
    $pdo = self::pdo($cfg);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (int)$pdo->lastInsertId();
  }

  public static function begin(array $cfg): void { self::pdo($cfg)->beginTransaction(); }
  public static function commit(array $cfg): void { self::pdo($cfg)->commit(); }
  public static function rollBack(array $cfg): void { self::pdo($cfg)->rollBack(); }
}
