<?php

require_once 'db_connection.php';

try {
    $database = Database::getInstance();
    $pdo = $database->getPdo();
    echo "Conexão com o banco de dados 'pessoas' estabelecida com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
}

?>
