<?php

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user']; // Alterado de id para user
    $senha = $_POST['senha'];

    try {
        $database = Database::getInstance();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("SELECT * FROM pessoas WHERE user = :user");
        $stmt->bindParam(':user', $user); // Alterado de id para user
        $stmt->execute();

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data && password_verify($senha, $user_data["senha"])) {
            echo "Login bem-sucedido!";
            session_start();
            $_SESSION['start'] = time(); // Iniciar o tempo da sessão
            $_SESSION['user'] = $user_data['user']; // Armazenar o nome de usuário na sessão
            header("Location: index.php");
            exit();
        } else {
            echo "User ou senha incorretos.";
        }
    } catch (PDOException $e) {
        echo "Erro ao processar login: " . $e->getMessage();
    }
}

?>
