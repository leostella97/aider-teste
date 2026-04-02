<?php

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $user = $_POST['user'];
    $senha = $_POST['senha'];

    try {
        $database = Database::getInstance();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("INSERT INTO dados (nome, user, pass) VALUES (:nome, :user, :pass)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':user', $user);
        $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
        $stmt->bindParam(':pass', $senha_hashed);
        $stmt->execute();

        echo "Usuário cadastrado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao cadastrar usuário: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
</head>
<body>
    <h1>Cadastre um novo usuário</h1>
    <form method="post" action="">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome"><br><br>
        <label for="user">User:</label>
        <input type="text" id="user" name="user"><br><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha"><br><br>
        <button type="submit">Cadastrar</button>
    </form>
    <br>
    <a href="index.php">Voltar ao Menu</a>
</body>
</html>
