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

            // Cria a tabela 'dados' se ela não existir
            $stmt = $this->pdo->prepare("
                CREATE TABLE IF NOT EXISTS dados (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nome VARCHAR(255) NOT NULL,
                    user VARCHAR(255) NOT NULL UNIQUE,
                    pass VARCHAR(255) NOT NULL
                )
            ");
            $stmt->execute();
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
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
