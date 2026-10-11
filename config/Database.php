<?php

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    // private: tidak bisa dibuat dengan "new Database()" dari luar
    private function __construct()
    {
        $dsn = 'mysql:host=127.0.0.1;dbname=inventaris_db;charset=utf8mb4';

        $this->pdo = new PDO($dsn, 'root', '', [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    // cegah penggandaan objek
    private function __clone() {}

    public function __wakeup()
    {
        throw new Exception('Singleton tidak boleh di-unserialize.');
    }

    // satu-satunya pintu untuk mendapat objek Database
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}