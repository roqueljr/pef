<?php

namespace App\Core\Database;

use PDO;
use PDOException;

require_once 'config/database.php';
/**
 * Database class for handling database connections.
 * This class will use the defined constants to connect to the database.
 */

class Database
{
    public function connect($dbIndex = 0): PDO
    {
        $db = DATABASE[$dbIndex];
        $dsn = "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'];
        $pdo = new PDO($dsn, $db['username'], $db['password']);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

    public static function query($sql, $params = [], $dbIndex = 0): array|string|bool|int
    {
        try {
            $db = new self();
            $pdo = $db->connect($dbIndex);
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            if (preg_match('/^\s*(SELECT)\b/i', $sql)) {
                return $stmt->fetchAll();
            } elseif (preg_match('/^\s*(INSERT)\b/i', $sql)) {
                return $pdo->lastInsertId();
            } elseif (preg_match('/^\s*(UPDATE|DELETE)\b/i', $sql)) {
                return $stmt->rowCount();
            }

            return true;

        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
}