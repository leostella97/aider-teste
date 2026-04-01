<?php

require_once 'db_connection.php';

$dbuser = 'admin';
$dbsenha = 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $senha = $_POST['senha'];

    try {
        $database = Database::getInstance();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("SELECT * FROM pessoas WHERE id = :id AND senha = :senha");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "Login bem-sucedido!";
        } else {
            echo "ID ou senha incorretos.";
        }
    } catch (PDOException $e) {
        echo "Erro ao processar login: " . $e->getMessage();
    }
}

?>
