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
            $auto_increment = "AUTO_INCREMENT";
        } catch (PDOException $e) {
            // Se MySQL falhar, tenta SQLite
            try {
                $this->pdo = new PDO("sqlite:pessoas.db");
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $auto_increment = "AUTOINCREMENT";
            } catch (PDOException $e2) {
                die("Connection failed: " . $e2->getMessage());
            }
        }

        $id_type = ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) == 'sqlite') ? 'INTEGER' : 'INT';
        $auto_increment_sql = ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) == 'sqlite') ? 'AUTOINCREMENT' : 'AUTO_INCREMENT';

        try {
            // Cria a tabela 'dados' se ela não existir
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS dados (
                    id $id_type PRIMARY KEY $auto_increment_sql,
                    nome VARCHAR(255) NOT NULL,
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
