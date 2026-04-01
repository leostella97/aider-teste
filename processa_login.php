<?php

require_once 'db_connection.php';

$dbuser = 'admin';
$dbsenha = 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user']; // Alterado de id para user
    $senha = $_POST['senha'];

    try {
        $database = Database::getInstance();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("SELECT * FROM pessoas WHERE user = :user AND senha = :senha");
        $stmt->bindParam(':user', $user); // Alterado de id para user
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            echo "Login bem-sucedido!";
        } else {
            echo "User ou senha incorretos.";
        }
    } catch (PDOException $e) {
        echo "Erro ao processar login: " . $e->getMessage();
    }
}

?>
