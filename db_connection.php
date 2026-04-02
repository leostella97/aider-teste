<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host = 'localhost';
        $dbname = 'pessoas';
        $username = 'root';
        $password = '';

        try {
            // Tenta MySQL primeiro
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Se MySQL falhar, tenta SQLite
            try {
                $this->pdo = new PDO("sqlite:pessoas.db");
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e2) {
                die("Connection failed: " . $e2->getMessage());
            }
        }

        try {
            $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
            $auto_increment = ($driver == 'sqlite') ? 'AUTOINCREMENT' : 'AUTO_INCREMENT';
            $id_type = ($driver == 'sqlite') ? 'INTEGER' : 'INT';

        try {
            // Cria a tabela 'dados' se ela não existir
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS dados (
                    id $id_type PRIMARY KEY $auto_increment,
                    nome VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    user VARCHAR(255) NOT NULL UNIQUE,
                    pass VARCHAR(255) NOT NULL
                )
            ");
        } catch (PDOException $e) {
            die("Table creation failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getPdo() {
        return $this->pdo;
    }
}

?>
